<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BahanBakuController extends Controller
{
    /**
     * Menu Monitoring Stok - hanya menampilkan data, tidak ada aksi tambah/kurang manual.
     * Stok hanya berubah lewat Bahan Masuk (tambah) dan penyelesaian pesanan (kurang otomatis).
     */
    public function index(Request $request): View
    {
        $bahanBaku = BahanBaku::query()
            ->when($request->search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            })
            ->when($request->boolean('hampir_habis'), function ($query) {
                $query->whereColumn('stok', '<=', 'stok_minimum');
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('bahan-baku.index', [
            'bahanBaku' => $bahanBaku,
            'search' => $request->search,
            'hampirHabis' => $request->boolean('hampir_habis'),
        ]);
    }

    /**
     * Form tambah data master bahan baku baru.
     * Hanya untuk bahan baku jenis baru yang belum pernah dicatat sama sekali.
     * Diakses CIO Marketing, karena CIO Marketing yang bertanggung jawab
     * atas pengadaan/pembelian bahan baku.
     */
    public function create(): View
    {
        return view('bahan-baku.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:bahan_baku,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'satuan' => ['required', 'string', 'max:20'],
            'stok' => ['required', 'numeric', 'min:0'],
            'stok_minimum' => ['required', 'numeric', 'min:0'],
        ]);

        BahanBaku::create($data);

        return redirect()->route('bahan-baku.index')->with('success', 'Data bahan baku baru berhasil ditambahkan.');
    }
}
