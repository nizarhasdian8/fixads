<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\BahanMasuk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BahanMasukController extends Controller
{
    public function index(Request $request): View
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $bahanMasuk = BahanMasuk::query()
            ->with(['bahanBaku', 'pencatat'])
            ->when($bulan, function ($query, $bulan) {
                $query->whereMonth('tanggal', $bulan);
            })
            ->when($tahun, function ($query, $tahun) {
                $query->whereYear('tanggal', $tahun);
            })
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $tahunOptions = BahanMasuk::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('bahan-masuk.index', [
            'bahanMasuk' => $bahanMasuk,
            'bulanFilter' => $bulan,
            'tahunFilter' => $tahun,
            'namaBulan' => $namaBulan,
            'tahunOptions' => $tahunOptions,
        ]);
    }

    public function create(): View
    {
        return view('bahan-masuk.create', [
            'bahanBakuList' => BahanBaku::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'bahan_baku_id' => ['required', 'exists:bahan_baku,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'nama_supplier' => ['required', 'string', 'max:100'],
            'foto_struk' => ['required', 'image', 'max:2048'], // Wajib upload struk max 2MB
        ], [
            'tanggal.required' => 'Harap isi tanggal bahan masuk.',
            'bahan_baku_id.required' => 'Harap pilih bahan baku.',
            'jumlah.required' => 'Harap isi jumlah bahan masuk.',
            'jumlah.integer' => 'Jumlah harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'nama_supplier.required' => 'Harap isi nama supplier.',
            'foto_struk.required' => 'Harap upload foto struk pembelian.',
            'foto_struk.image' => 'File harus berupa gambar.',
            'foto_struk.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        DB::transaction(function () use ($data, $request) {
            // Simpan foto struk ke folder public/struk-supplier
            if ($request->hasFile('foto_struk')) {
                $file = $request->file('foto_struk');
                $filename = $file->hashName();
                $file->move(public_path('struk-supplier'), $filename);
                $data['foto_struk'] = 'struk-supplier/' . $filename;
            }

            $data['nomor_transaksi'] = $this->generateNomorTransaksi();
            $data['dicatat_oleh'] = $request->user()->id;

            BahanMasuk::create($data);

            // Stok bertambah otomatis setelah bahan masuk dicatat.
            BahanBaku::where('id', $data['bahan_baku_id'])->increment('stok', $data['jumlah']);
        });

        return redirect()->route('bahan-masuk.index')->with('success', 'Bahan masuk berhasil dicatat, stok telah diperbarui.');
    }

    private function generateNomorTransaksi(): string
    {
        $prefix = 'BM'.now()->format('dmy');
        $urutanTerakhir = BahanMasuk::where('nomor_transaksi', 'like', "{$prefix}%")->count();
        $urutanBaru = str_pad((string) ($urutanTerakhir + 1), 3, '0', STR_PAD_LEFT);
        return "{$prefix}{$urutanBaru}";
    }
}