@extends('layouts.app')

@section('title', 'Buat Pesanan')

@section('content')
<div class="mb-6">
    <a href="{{ route('pesanan.index') }}" class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-900 mb-3">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Kembali
    </a>
    <h1 class="text-2xl font-bold text-stone-900">Buat Pesanan Baru</h1>
    <p class="text-stone-500 text-sm mt-1">Nomor invoice akan dibuat otomatis setelah pesanan disimpan.</p>
</div>

<form method="POST" action="{{ route('pesanan.store') }}" enctype="multipart/form-data" class="max-w-3xl">
    @csrf

    <div class="bg-white border border-stone-200 rounded-2xl p-6 space-y-5">
        <h2 class="font-semibold text-stone-900 border-b border-stone-100 pb-3">Data Customer</h2>
        <div class="grid sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Nama Customer</label>
                <input type="text" name="nama_customer" value="{{ old('nama_customer') }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('nama_customer') border-red-400 @enderror">
                @error('nama_customer') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Nomor Handphone</label>
                <input type="text" name="nomor_hp" value="{{ old('nomor_hp') }}" placeholder="08xxxxxxxxxx"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('nomor_hp') border-red-400 @enderror">
                @error('nomor_hp') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Sumber Pesanan</label>
                <select name="sumber_pesanan"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('sumber_pesanan') border-red-400 @enderror">
                    <option value="" disabled selected>Pilih sumber pesanan</option>
                    @foreach(['WhatsApp', 'E-commerce', 'Datang Langsung', 'Telepon', 'Referensi'] as $sumber)
                        <option value="{{ $sumber }}" @selected(old('sumber_pesanan') === $sumber)>{{ $sumber }}</option>
                    @endforeach
                </select>
                @error('sumber_pesanan') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Deadline</label>
                <input type="date" name="deadline" value="{{ old('deadline') }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('deadline') border-red-400 @enderror">
                @error('deadline') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="bg-white border border-stone-200 rounded-2xl p-6 space-y-5 mt-6">
        <h2 class="font-semibold text-stone-900 border-b border-stone-100 pb-3">Detail Produk</h2>
        <div class="grid sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Jenis Produk</label>
                <select name="produk_id"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('produk_id') border-red-400 @enderror">
                    <option value="" disabled selected>Pilih jenis produk</option>
                    @foreach($produkList as $produk)
                        <option value="{{ $produk->id }}" @selected((int) old('produk_id') === $produk->id)>{{ $produk->nama_produk }}</option>
                    @endforeach
                </select>
                @error('produk_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                @if($produkList->isEmpty())
                    <p class="text-xs text-amber-600 mt-1.5">Belum ada data produk. <a href="{{ route('produk.create') }}" class="underline font-medium">Tambah produk dulu</a>.</p>
                @endif
            </div>
            
            {{-- INPUT UKURAN DIPISAH JADI PANJANG LEBAR TINGGI --}}
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Ukuran (P x L x T)</label>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <input type="number" name="panjang" value="{{ old('panjang') }}" min="0" placeholder="Panjang"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('panjang') border-red-400 @enderror">
                        @error('panjang') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <input type="number" name="lebar" value="{{ old('lebar') }}" min="0" placeholder="Lebar"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('lebar') border-red-400 @enderror">
                        @error('lebar') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <input type="number" name="tinggi" value="{{ old('tinggi') }}" min="0" placeholder="Tinggi"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('tinggi') border-red-400 @enderror">
                        @error('tinggi') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Jumlah</label>
                <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('jumlah') border-red-400 @enderror">
                @error('jumlah') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga') }}" min="0" step="1000" placeholder="0"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('harga') border-red-400 @enderror">
                @error('harga') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Spesifikasi</label>
                <textarea name="spesifikasi" rows="3" placeholder="Detail spesifikasi produk yang diminta customer..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('spesifikasi') border-red-400 @enderror">{{ old('spesifikasi') }}</textarea>
                @error('spesifikasi') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Upload File Desain</label>
                <label for="file_desain" class="flex items-center justify-center gap-3 border-2 border-dashed border-stone-300 rounded-xl px-4 py-6 cursor-pointer hover:border-brand-400 hover:bg-brand-50/30 transition">
                    <svg class="w-6 h-6 text-stone-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                    <span class="text-sm text-stone-500"><span id="file-label">Klik untuk pilih gambar desain</span> &middot; PNG, JPG maks 5MB</span>
                </label>
                <input id="file_desain" type="file" name="file_desain" accept="image/*" class="hidden" onchange="document.getElementById('file-label').textContent = this.files[0]?.name || 'Klik untuk pilih gambar desain'">
                @error('file_desain') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Catatan Tambahan</label>
                <textarea name="catatan" rows="2" placeholder="Catatan opsional dari customer..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('catatan') border-red-400 @enderror">{{ old('catatan') }}</textarea>
                @error('catatan') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 mt-6">
        <a href="{{ route('pesanan.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-sm font-medium text-stone-700 hover:bg-stone-50 transition">Batal</a>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm transition shadow-sm shadow-brand-500/20">Simpan Pesanan</button>
    </div>
</form>
@endsection