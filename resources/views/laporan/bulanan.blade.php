@extends('layouts.app')

@section('title', 'Laporan Bulanan Bahan Baku')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-stone-900">Laporan Bulanan Bahan Baku</h1>
        <p class="text-stone-500 text-sm mt-1">Rekap data bahan baku yang masuk dan keluar per bulan.</p>
    </div>
    <a href="{{ route('laporan.bulanan.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold text-sm rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
        Download PDF
    </a>
</div>

{{-- Form Filter Bulan & Tahun --}}
<div class="bg-white border border-stone-200 rounded-2xl p-5 mb-6">
    <form method="GET" action="{{ route('laporan.bulanan') }}" class="flex flex-col sm:flex-row sm:items-end gap-4">
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Pilih Bulan</label>
            <select name="bulan" class="px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                @foreach($namaBulan as $num => $name)
                <option value="{{ $num }}" @selected((string)$bulan === (string)$num)>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Pilih Tahun</label>
            <select name="tahun" class="px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                @foreach($tahunOptions as $th)
                <option value="{{ $th }}" @selected((string)$tahun === (string)$th)>{{ $th }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm transition shadow-sm">
            Tampilkan Laporan
        </button>
    </form>
</div>

<div class="mb-4 text-sm font-medium text-stone-700">
    Menampilkan data untuk bulan: <span class="text-brand-600">{{ $namaBulan[(int)$bulan] }} {{ $tahun }}</span>
</div>

{{-- Ringkasan --}}
<div class="grid sm:grid-cols-2 gap-5 mb-6">
    <div class="bg-white border border-stone-200 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4M10 18l-6-6 6-6" /></svg>
        </div>
        <div>
            <p class="text-sm text-stone-500">Total Bahan Keluar (Pemakaian)</p>
            <p class="text-xl font-bold text-stone-900">{{ $totalKeluar }} Transaksi</p>
        </div>
    </div>
    <div class="bg-white border border-stone-200 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16M14 6l6 6-6 6" /></svg>
        </div>
        <div>
            <p class="text-sm text-stone-500">Total Bahan Masuk (Penerimaan)</p>
            <p class="text-xl font-bold text-stone-900">{{ $totalMasuk }} Transaksi</p>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    {{-- Tabel Bahan Keluar --}}
    <div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-stone-100">
            <h2 class="font-semibold text-stone-900">Bahan Keluar</h2>
            <p class="text-xs text-stone-400 mt-0.5">Bahan yang digunakan untuk produksi pesanan</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-stone-50 text-stone-500 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                        <th class="px-5 py-3 font-medium">Bahan Baku</th>
                        <th class="px-5 py-3 font-medium">Jumlah</th>
                        <th class="px-5 py-3 font-medium">Pesanan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($bahanKeluar as $item)
                    <tr class="hover:bg-stone-50">
                        <td class="px-5 py-3 text-stone-700">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                        <td class="px-5 py-3 text-stone-900 font-medium">{{ $item->bahanBaku->nama }}</td>
                        <td class="px-5 py-3 text-stone-700">{{ rtrim(rtrim($item->jumlah_pakai, '0'), '.') }} {{ $item->bahanBaku->satuan }}</td>
                        <td class="px-5 py-3 text-stone-700">
                            <a href="{{ route('pesanan.show', $item->pesanan_id) }}" class="text-brand-600 hover:underline">{{ $item->pesanan->nomor_invoice }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-stone-400">Tidak ada bahan keluar pada bulan ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabel Bahan Masuk --}}
    <div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-stone-100">
            <h2 class="font-semibold text-stone-900">Bahan Masuk</h2>
            <p class="text-xs text-stone-400 mt-0.5">Bahan yang diterima dari supplier</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-stone-50 text-stone-500 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                        <th class="px-5 py-3 font-medium">Kode Transaksi</th>
                        <th class="px-5 py-3 font-medium">Bahan Baku</th>
                        <th class="px-5 py-3 font-medium">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($bahanMasuk as $item)
                    <tr class="hover:bg-stone-50">
                        <td class="px-5 py-3 text-stone-700">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        <td class="px-5 py-3 text-stone-900 font-medium">{{ $item->nomor_transaksi }}</td>
                        <td class="px-5 py-3 text-stone-700">{{ $item->bahanBaku->nama }}</td>
                        <td class="px-5 py-3 text-stone-700">{{ $item->jumlah }} {{ $item->bahanBaku->satuan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-stone-400">Tidak ada bahan masuk pada bulan ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection