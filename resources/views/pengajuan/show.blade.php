@extends('layouts.app')

@section('title', $pengajuan->nomor_permintaan)

@section('content')
<div class="mb-6">
    <a href="{{ route('pengajuan.index') }}" class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-900 mb-3">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Kembali
    </a>
    <div class="flex flex-wrap items-center gap-3">
        <h1 class="text-2xl font-bold text-stone-900">{{ $pengajuan->nomor_permintaan }}</h1>
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $pengajuan->statusBadgeClass() }}">{{ $pengajuan->statusLabel() }}</span>
    </div>
    <p class="text-stone-500 text-sm mt-1">Diajukan oleh {{ $pengajuan->pengaju->name }} &middot; {{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white border border-stone-200 rounded-2xl p-6">
        <h2 class="font-semibold text-stone-900 mb-4">Detail Permintaan</h2>
        <dl class="grid sm:grid-cols-2 gap-y-4 gap-x-6 text-sm">
            <div>
                <dt class="text-stone-400">Bahan Baku</dt>
                <dd class="text-stone-900 font-medium mt-0.5">{{ $pengajuan->bahanBaku->nama }}</dd>
            </div>
            <div>
                <dt class="text-stone-400">Jumlah Diminta</dt>
                <dd class="text-stone-900 font-medium mt-0.5">{{ rtrim(rtrim($pengajuan->jumlah, '0'), '.') }} {{ $pengajuan->bahanBaku->satuan }}</dd>
            </div>
            <div>
                <dt class="text-stone-400">Stok Saat Ini</dt>
                <dd class="text-stone-900 font-medium mt-0.5">{{ rtrim(rtrim($pengajuan->bahanBaku->stok, '0'), '.') }} {{ $pengajuan->bahanBaku->satuan }}</dd>
            </div>
            <div>
                <dt class="text-stone-400">Pesanan Terkait</dt>
                <dd class="text-stone-900 font-medium mt-0.5">
                    @if($pengajuan->pesanan)
                        <a href="{{ route('pesanan.show', $pengajuan->pesanan) }}" class="text-brand-600 hover:underline">{{ $pengajuan->pesanan->nomor_invoice }}</a>
                    @else
                        —
                    @endif
                </dd>
            </div>
            @if($pengajuan->catatan)
            <div class="sm:col-span-2">
                <dt class="text-stone-400">Catatan</dt>
                <dd class="text-stone-700 mt-0.5">{{ $pengajuan->catatan }}</dd>
            </div>
            @endif
            @if($pengajuan->pemroses)
            <div class="sm:col-span-2 pt-3 border-t border-stone-100">
                <dt class="text-stone-400">Diproses Oleh</dt>
                <dd class="text-stone-900 font-medium mt-0.5">{{ $pengajuan->pemroses->name }} &middot; {{ $pengajuan->diproses_at->format('d M Y, H:i') }}</dd>
            </div>
            @endif
        </dl>

        @if($pengajuan->bahanMasuk)
        <div class="mt-5 pt-5 border-t border-stone-100">
            <p class="text-sm text-stone-500">Permintaan ini sudah dipenuhi lewat Bahan Masuk <span class="font-medium text-stone-900">{{ $pengajuan->bahanMasuk->nomor_transaksi }}</span> pada {{ $pengajuan->bahanMasuk->tanggal->format('d M Y') }}.</p>
        </div>
        @endif
    </div>

    <div class="space-y-4">
        @if(auth()->user()->isMarketing() && $pengajuan->status === 'pending')
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-4">Tindakan</h2>
            <div class="space-y-2.5">
                <form method="POST" action="{{ route('pengajuan.update-status', $pengajuan) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm py-2.5 rounded-xl transition shadow-sm shadow-brand-500/20">Setujui Permintaan</button>
                </form>
            </div>
        </div>
        @endif

        @if(auth()->user()->isMarketing() && $pengajuan->status === 'approved' && !$pengajuan->bahanMasuk)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
            <p class="text-sm text-amber-800 mb-3">Permintaan sudah disetujui. Catat bahan masuk setelah barang tiba dari supplier.</p>
            <a href="{{ route('bahan-masuk.create', ['permintaan_id' => $pengajuan->id]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-amber-700 hover:text-amber-900">
                Catat Bahan Masuk &rarr;
            </a>
        </div>
        @endif
    </div>
</div>
@endsection