@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-stone-900">Dashboard</h1>
    <p class="text-stone-500 text-sm mt-1">Ringkasan stok bahan baku dan antrian produksi.</p>
</div>

{{-- Stat cards baru --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    {{-- KPI 1: Bahan Menipis --}}
    <a href="{{ route('bahan-baku.index') }}" class="bg-white border border-stone-200 rounded-2xl p-5 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">Bahan Menipis</p>
            <p class="text-3xl font-bold text-stone-900 mt-1">{{ $bahanMenipis }}</p>
            <p class="text-xs text-stone-500">Item perlu diajukan</p>
        </div>
    </a>

    {{-- KPI 2: Bahan Masuk Hari Ini --}}
    <a href="{{ route('laporan.harian') }}" class="bg-white border border-stone-200 rounded-2xl p-5 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16M14 6l6 6-6 6" /></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">Bahan Masuk</p>
            <p class="text-3xl font-bold text-stone-900 mt-1">{{ $bahanMasukHariIni }}</p>
            <p class="text-xs text-stone-500">Transaksi hari ini</p>
        </div>
    </a>

    {{-- KPI 3: Bahan Keluar Hari Ini --}}
    <a href="{{ route('laporan.harian') }}" class="bg-white border border-stone-200 rounded-2xl p-5 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4M10 18l-6-6 6-6" /></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">Bahan Keluar</p>
            <p class="text-3xl font-bold text-stone-900 mt-1">{{ $bahanKeluarHariIni }}</p>
            <p class="text-xs text-stone-500">Pemakaian hari ini</p>
        </div>
    </a>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    {{-- Bahan hampir habis --}}
    <div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-stone-200">
            <h2 class="font-semibold text-stone-900">Stok Hampir Habis</h2>
            <a href="{{ route('bahan-baku.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Lihat semua &rarr;</a>
        </div>
        <div class="divide-y divide-stone-100">
            @forelse($bahanHampirHabis as $bahan)
            <div class="px-5 py-3.5 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-stone-900">{{ $bahan->nama }}</p>
                    <p class="text-xs text-stone-400">Min. stok: {{ floatval($bahan->stok_minimum) }} {{ $bahan->satuan }}</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20">
                    {{ floatval($bahan->stok) }} {{ $bahan->satuan }}
                </span>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-stone-400 text-sm">Semua stok aman.</div>
            @endforelse
        </div>
    </div>

    {{-- Antrian produksi --}}
    <div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-stone-200">
            <h2 class="font-semibold text-stone-900">Antrian Produksi</h2>
            <a href="{{ route('pesanan.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Lihat semua &rarr;</a>
        </div>
        <div class="divide-y divide-stone-100">
            @forelse($pesananAntrian as $item)
            <div class="px-5 py-3.5 flex items-center justify-between cursor-pointer hover:bg-stone-50" onclick="window.location='{{ route('pesanan.show', $item) }}'">
                <div>
                    <p class="text-sm font-medium text-stone-900">{{ $item->nomor_invoice }}</p>
                    <p class="text-xs text-stone-400">{{ $item->produk->nama_produk }} &middot; {{ $item->nama_customer }}</p>
                    <p class="text-xs text-stone-500 mt-0.5">Teknisi: <span class="font-medium text-stone-700">{{ $item->teknisi->nama ?? 'Belum dipilih' }}</span></p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $item->statusBadgeClass() }}">{{ $item->statusLabel() }}</span>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-stone-400 text-sm">Tidak ada antrian.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection