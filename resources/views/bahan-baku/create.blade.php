@extends('layouts.app')

@section('title', 'Tambah Bahan Baku')

@section('content')
<div class="mb-6">
    <a href="{{ route('bahan-baku.index') }}" class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-900 mb-3">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Kembali
    </a>
    <h1 class="text-2xl font-bold text-stone-900">Tambah Bahan Baku Baru</h1>
    <p class="text-stone-500 text-sm mt-1">Gunakan ini hanya untuk jenis bahan baku yang belum pernah tercatat.</p>
</div>

<form method="POST" action="{{ route('bahan-baku.store') }}" class="max-w-2xl">
    @csrf
    <div class="bg-white border border-stone-200 rounded-2xl p-6 space-y-5">
        <div class="grid sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Kode Bahan</label>
                <input type="text" name="kode" value="{{ old('kode') }}" required placeholder="cth. BB-001"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('kode') border-red-400 @enderror">
                @error('kode') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Nama Bahan</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="cth. Acrylic 3mm"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('nama') border-red-400 @enderror">
                @error('nama') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Kategori</label>
                <input type="text" name="kategori" value="{{ old('kategori') }}" required placeholder="cth. Bahan Utama"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('kategori') border-red-400 @enderror">
                @error('kategori') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Satuan</label>
                <input type="text" name="satuan" value="{{ old('satuan') }}" required placeholder="cth. lembar, meter, pcs"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('satuan') border-red-400 @enderror">
                @error('satuan') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Stok Awal</label>
                <input type="number" step="0.01" min="0" name="stok" value="{{ old('stok', 0) }}" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('stok') border-red-400 @enderror">
                @error('stok') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Stok Minimum</label>
                <input type="number" step="0.01" min="0" name="stok_minimum" value="{{ old('stok_minimum', 0) }}" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('stok_minimum') border-red-400 @enderror">
                @error('stok_minimum') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-stone-400 mt-1">Jika stok di bawah angka ini, status berubah jadi "Hampir Habis".</p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 mt-6">
        <a href="{{ route('bahan-baku.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-sm font-medium text-stone-700 hover:bg-stone-50 transition">Batal</a>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm transition shadow-sm shadow-brand-500/20">Simpan</button>
    </div>
</form>
@endsection
