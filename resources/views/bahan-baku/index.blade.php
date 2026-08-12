@extends('layouts.app')

@section('title', auth()->user()->isMarketing() ? 'Status Produksi' : 'Monitoring Stok')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-stone-900">Monitoring Stok Bahan Baku</h1>
        <p class="text-stone-500 text-sm mt-1">Pantau ketersediaan bahan baku secara real-time.</p>
    </div>
    @if(auth()->user()->isMarketing())
    <a href="{{ route('bahan-baku.create') }}" class="inline-flex items-center justify-center gap-2 bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition shadow-sm shadow-brand-500/20 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
        Tambah Bahan Baku
    </a>
    @endif
</div>

<form method="GET" class="bg-white border border-stone-200 rounded-2xl p-4 mb-6 flex flex-col sm:flex-row gap-3">
    <div class="flex-1 relative">
        <svg class="w-4 h-4 text-stone-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M19 11a8 8 0 11-16 0 8 8 0 0116 0z" /></svg>
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau kode bahan baku..."
            class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-stone-300 text-sm placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
    </div>
    <label class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-stone-300 cursor-pointer select-none">
        <input type="checkbox" name="hampir_habis" value="1" @checked($hampirHabis) onchange="this.form.submit()" class="rounded border-stone-300 text-brand-600 focus:ring-brand-500">
        <span class="text-sm text-stone-700 whitespace-nowrap">Hampir habis saja</span>
    </label>
    <button type="submit" class="px-5 py-2.5 rounded-xl border border-stone-300 text-sm font-medium text-stone-700 hover:bg-stone-50 transition shrink-0">Filter</button>
</form>

<div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-stone-400 uppercase tracking-wide border-b border-stone-100">
                    <th class="px-5 py-3 font-medium">Kode</th>
                    <th class="px-5 py-3 font-medium">Nama Bahan</th>
                    <th class="px-5 py-3 font-medium">Kategori</th>
                    <th class="px-5 py-3 font-medium">Stok Sekarang</th>
                    <th class="px-5 py-3 font-medium">Stok Minimum</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($bahanBaku as $item)
                <tr class="hover:bg-stone-50">
                    <td class="px-5 py-3.5 font-medium text-stone-900">{{ $item->kode }}</td>
                    <td class="px-5 py-3.5 text-stone-700">{{ $item->nama }}</td>
                    <td class="px-5 py-3.5 text-stone-500">{{ $item->kategori }}</td>
                    <td class="px-5 py-3.5 text-stone-700">{{ rtrim(rtrim($item->stok, '0'), '.') }} {{ $item->satuan }}</td>
                    <td class="px-5 py-3.5 text-stone-500">{{ rtrim(rtrim($item->stok_minimum, '0'), '.') }} {{ $item->satuan }}</td>
                    <td class="px-5 py-3.5">
                        @if($item->isHampirHabis())
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20">Hampir Habis</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Aman</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-stone-400">Belum ada data bahan baku.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bahanBaku->hasPages())
    <div class="px-5 py-4 border-t border-stone-100">{{ $bahanBaku->links() }}</div>
    @endif
</div>
@endsection
