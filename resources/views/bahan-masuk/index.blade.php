@extends('layouts.app')

@section('title', 'Bahan Masuk')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-stone-900">Bahan Masuk</h1>
        <p class="text-stone-500 text-sm mt-1">Riwayat pencatatan bahan baku yang datang dari supplier.</p>
    </div>
    <a href="{{ route('bahan-masuk.create') }}" class="inline-flex items-center justify-center gap-2 bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition shadow-sm shadow-brand-500/20 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
        Catat Bahan Masuk
    </a>
</div>

{{-- Filters --}}
<form method="GET" class="bg-white border border-stone-200 rounded-2xl p-4 mb-6 flex flex-col sm:flex-row gap-3">
    <div class="flex gap-3">
        {{-- Filter Bulan --}}
        <select name="bulan" class="px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
            <option value="">Semua Bulan</option>
            @foreach($namaBulan as $num => $name)
            <option value="{{ $num }}" @selected((string)$bulanFilter === (string)$num)>{{ $name }}</option>
            @endforeach
        </select>

        {{-- Filter Tahun --}}
        <select name="tahun" class="px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
            <option value="">Semua Tahun</option>
            @foreach($tahunOptions as $th)
            <option value="{{ $th }}" @selected((string)$tahunFilter === (string)$th)>{{ $th }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="px-5 py-2.5 rounded-xl bg-stone-800 text-white text-sm font-medium hover:bg-stone-700 transition shrink-0">Filter</button>
</form>

<div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-stone-400 uppercase tracking-wide border-b border-stone-100">
                    <th class="px-5 py-3 font-medium">No. Transaksi</th>
                    <th class="px-5 py-3 font-medium">Tanggal</th>
                    <th class="px-5 py-3 font-medium">Bahan Baku</th>
                    <th class="px-5 py-3 font-medium">Jumlah</th>
                    <th class="px-5 py-3 font-medium">Permintaan Terkait</th>
                    <th class="px-5 py-3 font-medium">Dicatat Oleh</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($bahanMasuk as $item)
                <tr class="hover:bg-stone-50">
                    <td class="px-5 py-3.5 font-medium text-stone-900">{{ $item->nomor_transaksi }}</td>
                    <td class="px-5 py-3.5 text-stone-600">{{ $item->tanggal->format('d M Y') }}</td>
                    <td class="px-5 py-3.5 text-stone-700">{{ $item->bahanBaku->nama }}</td>
                    <td class="px-5 py-3.5 text-stone-600">+{{ rtrim(rtrim($item->jumlah, '0'), '.') }} {{ $item->bahanBaku->satuan }}</td>
                    <td class="px-5 py-3.5 text-stone-500">
                        @if($item->permintaanBahan)
                            <a href="{{ route('pengajuan.show', $item->permintaanBahan) }}" class="text-brand-600 hover:underline">{{ $item->permintaanBahan->nomor_permintaan }}</a>
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-stone-500">{{ $item->pencatat->name }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-stone-400">Belum ada riwayat bahan masuk pada bulan ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bahanMasuk->hasPages())
    <div class="px-5 py-4 border-t border-stone-100">{{ $bahanMasuk->links() }}</div>
    @endif
</div>
@endsection