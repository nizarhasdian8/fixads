@extends('layouts.app')

@section('title', 'Data Produk')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-stone-900">Data Produk</h1>
        <p class="text-stone-500 text-sm mt-1">Daftar jenis produk yang ditawarkan Fix Advertising.</p>
    </div>
    <a href="{{ route('produk.create') }}" class="inline-flex items-center justify-center gap-2 bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition shadow-sm shadow-brand-500/20 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
        Tambah Produk
    </a>
</div>

<form method="GET" class="bg-white border border-stone-200 rounded-2xl p-4 mb-6">
    <div class="relative">
        <svg class="w-4 h-4 text-stone-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M19 11a8 8 0 11-16 0 8 8 0 0116 0z" /></svg>
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau kode produk..."
            class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-stone-300 text-sm placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
    </div>
</form>

<div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-stone-400 uppercase tracking-wide border-b border-stone-100">
                    <th class="px-5 py-3 font-medium">Kode</th>
                    <th class="px-5 py-3 font-medium">Nama Produk</th>
                    <th class="px-5 py-3 font-medium">Deskripsi</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($produk as $item)
                <tr class="hover:bg-stone-50">
                    <td class="px-5 py-3.5 font-medium text-stone-900">{{ $item->kode_produk }}</td>
                    <td class="px-5 py-3.5 text-stone-700">{{ $item->nama_produk }}</td>
                    <td class="px-5 py-3.5 text-stone-500 max-w-xs truncate">{{ $item->deskripsi ?? '—' }}</td>
                    <td class="px-5 py-3.5">
                        @if($item->status_aktif)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Aktif</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-stone-100 text-stone-500 ring-1 ring-inset ring-stone-400/20">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('produk.edit', $item) }}" class="text-stone-400 hover:text-brand-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <form method="POST" action="{{ route('produk.destroy', $item) }}" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-stone-400 hover:text-red-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-stone-400">Belum ada data produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($produk->hasPages())
    <div class="px-5 py-4 border-t border-stone-100">{{ $produk->links() }}</div>
    @endif
</div>
@endsection
