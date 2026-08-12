@extends('layouts.app')

@section('title', 'Catat Bahan Masuk')

@section('content')
<div class="mb-6">
    <a href="{{ route('bahan-masuk.index') }}" class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-900 mb-3">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Kembali
    </a>
    <h1 class="text-2xl font-bold text-stone-900">Catat Bahan Masuk</h1>
    <p class="text-stone-500 text-sm mt-1">Nomor transaksi dibuat otomatis. Stok bahan baku akan bertambah setelah disimpan.</p>
</div>

<form method="POST" action="{{ route('bahan-masuk.store') }}" class="max-w-2xl">
    @csrf
    <div class="bg-white border border-stone-200 rounded-2xl p-6 space-y-5">
        <div class="grid sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('tanggal') border-red-400 @enderror">
                @error('tanggal') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div></div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Bahan Baku</label>
                <select name="bahan_baku_id"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('bahan_baku_id') border-red-400 @enderror">
                    <option value="" disabled selected>Pilih bahan baku</option>
                    @foreach($bahanBakuList as $bahan)
                    <option value="{{ $bahan->id }}" @selected((int) old('bahan_baku_id') === $bahan->id)>{{ $bahan->nama }}</option>
                    @endforeach
                </select>
                @error('bahan_baku_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1.5">Jumlah</label>
                <input type="number" step="0.01" min="0.01" name="jumlah" value="{{ old('jumlah') }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('jumlah') border-red-400 @enderror">
                @error('jumlah') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 mt-6">
        <a href="{{ route('bahan-masuk.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-sm font-medium text-stone-700 hover:bg-stone-50 transition">Batal</a>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm transition shadow-sm shadow-brand-500/20">Simpan</button>
    </div>
</form>
@endsection