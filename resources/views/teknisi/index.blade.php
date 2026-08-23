@extends('layouts.app')

@section('title', 'Data Teknisi')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-stone-900">Data Teknisi</h1>
    <p class="text-stone-500 text-sm mt-1">Daftar teknisi produksi beserta jumlah pengerjaan pesanan bulan ini.</p>
</div>

<div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-stone-400 uppercase tracking-wide border-b border-stone-100">
                    <th class="px-5 py-3 font-medium">No</th>
                    <th class="px-5 py-3 font-medium">Nama Teknisi</th>
                    <th class="px-5 py-3 font-medium text-center">Jumlah Pengerjaan (Bulan Ini)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($teknisis as $item)
                <tr class="hover:bg-stone-50">
                    <td class="px-5 py-3.5 text-stone-500">{{ $loop->iteration }}</td>
                    <td class="px-5 py-3.5 font-medium text-stone-900">{{ $item->nama }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-brand-50 text-brand-700 ring-1 ring-inset ring-brand-600/20">
                            {{ $item->pesanans_count }} Pesanan
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-5 py-10 text-center text-stone-400">Belum ada data teknisi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection