<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProdukController extends Controller
{
    public function index(Request $request): View
    {
        $produk = Produk::query()
            ->when($request->search, function ($query, $search) {
                $query->where('nama_produk', 'like', "%{$search}%")
                    ->orWhere('kode_produk', 'like', "%{$search}%");
            })
            ->orderBy('nama_produk')
            ->paginate(10)
            ->withQueryString();

        return view('produk.index', [
            'produk' => $produk,
            'search' => $request->search,
        ]);
    }

    public function create(): View
    {
        return view('produk.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode_produk' => ['required', 'string', 'max:50', 'unique:produk,kode_produk'],
            'nama_produk' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'status_aktif' => ['nullable', 'boolean'],
        ]);

        $data['status_aktif'] = $request->boolean('status_aktif');

        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Produk baru berhasil ditambahkan.');
    }

    public function edit(Produk $produk): View
    {
        return view('produk.edit', ['produk' => $produk]);
    }

    public function update(Request $request, Produk $produk): RedirectResponse
    {
        $data = $request->validate([
            'kode_produk' => ['required', 'string', 'max:50', 'unique:produk,kode_produk,'.$produk->id],
            'nama_produk' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'status_aktif' => ['nullable', 'boolean'],
        ]);

        $data['status_aktif'] = $request->boolean('status_aktif');

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Data produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk): RedirectResponse
    {
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
