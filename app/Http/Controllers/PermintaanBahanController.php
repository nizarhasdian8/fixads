<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\PermintaanBahan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class PermintaanBahanController extends Controller
{
    public function index(Request $request): View
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $permintaan = PermintaanBahan::query()
            ->with(['bahanBaku', 'pengaju'])
            ->when($bulan, function ($query, $bulan) {
                $query->whereMonth('created_at', $bulan);
            })
            ->when($tahun, function ($query, $tahun) {
                $query->whereYear('created_at', $tahun);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $tahunOptions = PermintaanBahan::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('pengajuan.index', [
            'permintaan' => $permintaan,
            'statusFilter' => $request->status,
            'statusOptions' => PermintaanBahan::statusOptions(),
            'bulanFilter' => $bulan,
            'tahunFilter' => $tahun,
            'namaBulan' => $namaBulan,
            'tahunOptions' => $tahunOptions,
        ]);
    }

    public function create(): View
    {
        return view('pengajuan.create', [
            'bahanBakuList' => BahanBaku::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'bahan_baku_id' => ['required', 'exists:bahan_baku,id'],
            'jumlah' => ['required', 'integer', 'min:1'], // Ubah ke integer (angka bulat)
            'catatan' => ['nullable', 'string'],
        ], [
            'bahan_baku_id.required' => 'Harap pilih bahan baku.',
            'jumlah.required' => 'Harap isi jumlah permintaan.',
            'jumlah.integer' => 'Jumlah harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah minimal 1.',
        ]);

        $data['nomor_permintaan'] = $this->generateNomorPermintaan();
        $data['status'] = 'pending';
        $data['diajukan_oleh'] = $request->user()->id;

        $permintaan = PermintaanBahan::create($data);

        return redirect()->route('pengajuan.index')
            ->with('success', "Permintaan {$permintaan->nomor_permintaan} berhasil diajukan, menunggu approval CIO Marketing.");
    }

    public function show(PermintaanBahan $pengajuan): View
    {
        $pengajuan->load(['bahanBaku', 'pengaju', 'pemroses', 'bahanMasuk']);

        return view('pengajuan.show', ['pengajuan' => $pengajuan]);
    }

    public function updateStatus(Request $request, PermintaanBahan $pengajuan): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:approved'], // Hanya menerima approved
        ]);

        $pengajuan->update([
            'status' => $data['status'],
            'diproses_oleh' => $request->user()->id,
            'diproses_at' => now(),
        ]);

        return redirect()->route('pengajuan.show', $pengajuan)->with('success', 'Permintaan disetujui. Silakan catat Bahan Masuk setelah barang tiba dari supplier.');
    }

    private function generateNomorPermintaan(): string
    {
        $prefix = 'PB'.now()->format('dmy');
        $urutanTerakhir = PermintaanBahan::where('nomor_permintaan', 'like', "{$prefix}%")->count();
        $urutanBaru = str_pad((string) ($urutanTerakhir + 1), 3, '0', STR_PAD_LEFT);
        return "{$prefix}{$urutanBaru}";
    }

    public function downloadPdf(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $status = $request->input('status');

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $permintaan = PermintaanBahan::query()
            ->with(['bahanBaku', 'pengaju'])
            ->when($bulan, function ($query, $bulan) {
                $query->whereMonth('created_at', $bulan);
            })
            ->when($tahun, function ($query, $tahun) {
                $query->whereYear('created_at', $tahun);
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->oldest()
            ->get();

        $pdf = Pdf::loadView('pengajuan.pdf', compact('permintaan', 'bulan', 'tahun', 'namaBulan', 'status'));
        
        $namaFile = 'laporan-permintaan-bahan-' . ($bulan ? $namaBulan[(int)$bulan] : 'semua') . '-' . ($tahun ?: 'tahun') . '.pdf';
        
        return $pdf->download($namaFile);
    }
}