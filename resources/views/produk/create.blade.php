@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="mb-6">
    <a href="{{ route('produk.index') }}" class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-900 mb-3">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Kembali
    </a>
    <h1 class="text-2xl font-bold text-stone-900">Tambah Produk Baru</h1>
</div>

<form method="POST" action="{{ route('produk.store') }}" class="max-w-2xl">
    @csrf
    <div class="bg-white border border-stone-200 rounded-2xl p-6 space-y-5">
        <div class="grid sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Kode Produk</label>
                <input type="text" name="kode_produk" value="{{ old('kode_produk') }}" required placeholder="cth. NBX-01"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('kode_produk') border-red-400 @enderror">
                @error('kode_produk') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Nama Produk</label>
                <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" required placeholder="cth. Neon Box"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('nama_produk') border-red-400 @enderror">
                @error('nama_produk') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat produk..." class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="status_aktif" value="1" checked class="rounded border-stone-300 text-brand-600 focus:ring-brand-500">
                    <span class="text-sm text-stone-700">Aktifkan produk ini (tampil sebagai pilihan saat input pesanan)</span>
                </label>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 mt-6">
        <a href="{{ route('produk.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-sm font-medium text-stone-700 hover:bg-stone-50 transition">Batal</a>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm transition shadow-sm shadow-brand-500/20">Simpan Produk</button>
    </div>
</form>
@endsection
