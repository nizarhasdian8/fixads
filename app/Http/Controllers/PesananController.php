<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class PesananController extends Controller
{
    public function index(Request $request): View
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $pesanan = Pesanan::query()
            ->with('produk')
            ->when($request->search, function ($query, $search) {
                $query->where('nama_customer', 'like', "%{$search}%")
                    ->orWhere('nomor_invoice', 'like', "%{$search}%");
            })
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

        $tahunOptions = Pesanan::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('pesanan.index', [
            'pesanan' => $pesanan,
            'search' => $request->search,
            'statusFilter' => $request->status,
            'statusOptions' => Pesanan::statusOptions(),
            'bulanFilter' => $bulan,
            'tahunFilter' => $tahun,
            'namaBulan' => $namaBulan,
            'tahunOptions' => $tahunOptions,
        ]);
    }

    public function create(): View
    {
        return view('pesanan.create', [
            'produkList' => Produk::where('status_aktif', true)->orderBy('nama_produk')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_customer' => ['required', 'string', 'max:255'],
            'nomor_hp' => ['required', 'string', 'min:11', 'max:15'],
            'sumber_pesanan' => ['required', 'string', 'max:100'],
            'produk_id' => ['required', 'exists:produk,id'],
            'panjang' => ['required', 'string', 'max:50'], 
            'lebar' => ['required', 'string', 'max:50'],   
            'tebal' => ['required', 'string', 'max:50'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'harga' => ['required', 'numeric', 'min:0'],
            'spesifikasi' => ['required', 'string'],
            'file_desain' => ['required', 'image', 'max:5120'],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
            'catatan' => ['nullable', 'string'],
            'status_pembayaran' => ['required', 'in:Belum Lunas,DP,Lunas'], 
            'bukti_pembayaran' => ['required', 'image', 'max:2048'], // UBAH MENJADI REQUIRED (WAJIB)
        ], [
            'nomor_hp.required' => 'Harap isi dengan benar.',
            'nomor_hp.min' => 'Harap isi dengan benar.',
            'nomor_hp.max' => 'Harap isi dengan benar.',
            'harga.required' => 'Harap isi dengan benar.',
            'harga.numeric' => 'Harap isi dengan benar.',
            'bukti_pembayaran.required' => 'Harap upload bukti pembayaran.', // TAMBAHAN PESAN ERROR KHUSUS
            'required' => 'Kolom :attribute wajib diisi.',
            'min' => 'Kolom :attribute minimal harus :min karakter.',
            'max' => 'Kolom :attribute maksimal harus :max karakter.',
            'integer' => 'Kolom :attribute harus berupa angka bulat.',
            'image' => 'File :attribute harus berupa gambar.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'after_or_equal' => 'Kolom :attribute harus tanggal hari ini atau setelahnya.',
            'exists' => 'Produk yang dipilih tidak valid.',
        ], [
            'nama_customer' => 'Nama Customer',
            'sumber_pesanan' => 'Sumber Pesanan',
            'produk_id' => 'Jenis Produk',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'tebal' => 'Tebal',
            'jumlah' => 'Jumlah',
            'spesifikasi' => 'Spesifikasi',
            'file_desain' => 'File Desain',
            'deadline' => 'Deadline',
            'catatan' => 'Catatan',
            'status_pembayaran' => 'Status Pembayaran',
            'bukti_pembayaran' => 'Bukti Pembayaran',
        ]);

        // GABUNGKAN PANJANG LEBAR TEBAL MENJADI 1 KOLOM UKURAN
        $data['ukuran'] = $data['panjang'] . ' x ' . $data['lebar'] . ' x ' . $data['tebal'];
        unset($data['panjang'], $data['lebar'], $data['tebal']);

        // UPLOAD BUKTI PEMBAYARAN (KWITANSI)
        if ($request->hasFile('bukti_pembayaran')) {
            $fileKwitansi = $request->file('bukti_pembayaran');
            $filenameKwitansi = $fileKwitansi->hashName();
            $fileKwitansi->move(public_path('kwitansi-pembayaran'), $filenameKwitansi);
            $data['bukti_pembayaran'] = 'kwitansi-pembayaran/' . $filenameKwitansi;
        }

        if ($request->hasFile('file_desain')) {
            $file = $request->file('file_desain');
            $filename = $file->hashName();
            $file->move(public_path('desain-pesanan'), $filename);
            $data['file_desain'] = 'desain-pesanan/' . $filename;
        }

        $data['nomor_invoice'] = $this->generateNomorInvoice();
        $data['status'] = 'queue';
        $data['created_by'] = $request->user()->id;

        $pesanan = Pesanan::create($data);

        return redirect()->route('pesanan.show', $pesanan)->with('success', "Pesanan {$pesanan->nomor_invoice} berhasil dibuat.");
    }

    public function show(Pesanan $pesanan): View
    {
        $pesanan->load(['produk', 'pembuat', 'pemakaianBahan.bahanBaku', 'permintaanBahan.bahanBaku']);

        return view('pesanan.show', [
            'pesanan' => $pesanan,
            'bahanBakuList' => BahanBaku::orderBy('nama')->get(),
            'statusOptions' => Pesanan::statusOptions(),
        ]);
    }

    public function updateStatus(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $user = $request->user();
        $currentStatus = $pesanan->status;
        $newStatus = $request->status;

        if ($newStatus === 'diterima') {
            if ($user->role !== 'cio_marketing') {
                return back()->with('error', 'Hanya CIO Marketing yang dapat mengubah status menjadi Diterima Pelanggan.');
            }
            if ($currentStatus !== 'completed') {
                return back()->with('error', 'Pesanan harus berstatus Selesai Produksi terlebih dahulu.');
            }
            
            $pesanan->update(['status' => 'diterima']);
            return redirect()->route('pesanan.show', $pesanan)->with('success', 'Status pesanan berhasil diubah menjadi Diterima Pelanggan.');
        }

        if ($currentStatus === 'completed') {
            return back()->with('error', 'Status pesanan sudah Selesai Produksi dan tidak dapat diubah lagi oleh CIO Production.');
        }

        if (in_array($currentStatus, ['processing', 'delayed']) && $newStatus === 'queue') {
            return back()->with('error', 'Status yang sudah Diproses tidak dapat dikembalikan ke Antrian.');
        }

        $data = $request->validate([
            'kode_teknisi' => ['required', 'string', 'max:50'],
            'status' => ['required', 'in:queue,processing,completed,delayed'],
            'bahan' => ['nullable', 'array'],
            'bahan.*.bahan_baku_id' => ['nullable', 'exists:bahan_baku,id'],
            'bahan.*.jumlah_pakai' => ['nullable', 'integer', 'min:1'], 
        ], [
            'bahan.*.jumlah_pakai.integer' => 'Jumlah pemakaian harus berupa angka bulat.',
            'bahan.*.jumlah_pakai.min' => 'Jumlah pemakaian minimal 1.',
        ]);

        $barisBahan = collect($data['bahan'] ?? [])
            ->filter(fn ($baris) => ! empty($baris['bahan_baku_id']) && ! empty($baris['jumlah_pakai']));

        // 1. CEK STOK SEBELUM MENYIMPAN
        $stokKurang = false;
        $namaBahanKurang = '';
        
        foreach ($barisBahan as $baris) {
            $bahanBaku = BahanBaku::find($baris['bahan_baku_id']);
            if ($bahanBaku && $bahanBaku->stok < $baris['jumlah_pakai']) {
                $stokKurang = true;
                $namaBahanKurang = $bahanBaku->nama;
                break; 
            }
        }

        // 2. JIKA STOK KURANG, UBAH STATUS JADI TERTUNDA & KASIH PESAN ERROR
        if ($stokKurang) {
            $pesanan->update([
                'kode_teknisi' => $data['kode_teknisi'],
                'status' => 'delayed',
            ]);

            return back()->with('error', "Bahan baku kurang! Stok {$namaBahanKurang} tidak mencukupi.")->withInput();
        }

        // 3. JIKA STOK AMAN, PROSES UPDATE STATUS & KURANGI STOK
        DB::transaction(function () use ($data, $pesanan, $barisBahan) {
            $pesanan->update([
                'kode_teknisi' => $data['kode_teknisi'],
                'status' => $data['status'],
            ]);

            foreach ($barisBahan as $baris) {
                $bahanBaku = BahanBaku::lockForUpdate()->find($baris['bahan_baku_id']);

                if (! $bahanBaku) {
                    continue;
                }

                $pesanan->pemakaianBahan()->create([
                    'bahan_baku_id' => $bahanBaku->id,
                    'jumlah_pakai' => $baris['jumlah_pakai'],
                ]);

                $bahanBaku->decrement('stok', $baris['jumlah_pakai']);
            }
        });

        return redirect()->route('pesanan.show', $pesanan)->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy(Pesanan $pesanan): RedirectResponse
    {
        if ($pesanan->file_desain) {
            $filePath = public_path($pesanan->file_desain);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        // Hapus juga bukti pembayaran jika ada
        if ($pesanan->bukti_pembayaran) {
            $filePathKwitansi = public_path($pesanan->bukti_pembayaran);
            if (File::exists($filePathKwitansi)) {
                File::delete($filePathKwitansi);
            }
        }
        $pesanan->pemakaianBahan()->delete();
        $pesanan->permintaanBahan()->delete();
        $pesanan->delete();

        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil dihapus permanen.');
    }

    private function generateNomorInvoice(): string
    {
        $prefix = 'INV'.now()->format('dmy');
        $urutanTerakhir = Pesanan::where('nomor_invoice', 'like', "{$prefix}%")->count();
        $urutanBaru = str_pad((string) ($urutanTerakhir + 1), 3, '0', STR_PAD_LEFT);
        return "{$prefix}{$urutanBaru}";
    }

    public function downloadPdf(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        $status = $request->input('status');
        $search = $request->input('search');

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $pesanan = Pesanan::query()
            ->with('produk')
            ->when($search, function ($query, $search) {
                $query->where('nama_customer', 'like', "%{$search}%")
                    ->orWhere('nomor_invoice', 'like', "%{$search}%");
            })
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

        $pdf = Pdf::loadView('pesanan.pdf', compact('pesanan', 'bulan', 'tahun', 'namaBulan', 'status'));
        
        $namaFile = 'laporan-pesanan-' . ($bulan ? $namaBulan[(int)$bulan] : 'semua') . '-' . ($tahun ?: 'tahun') . '.pdf';
        
        return $pdf->download($namaFile);
    }
}