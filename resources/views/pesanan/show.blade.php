@extends('layouts.app')

@section('title', $pesanan->nomor_invoice)

@section('content')
<div class="mb-6">
    <a href="{{ route('pesanan.index') }}" class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-900 mb-3">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Kembali
    </a>
    
    {{-- TOMBOL CETAK STRUK (HANYA CIO MARKETING) --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-3">
            <h1 class="text-2xl font-bold text-stone-900">{{ $pesanan->nomor_invoice }}</h1>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $pesanan->statusBadgeClass() }}">{{ $pesanan->statusLabel() }}</span>
        </div>
        @if(auth()->user()->isMarketing())
        <a href="{{ route('pesanan.struk', $pesanan) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-stone-800 hover:bg-stone-700 text-white font-semibold text-sm rounded-xl transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Cetak Struk
        </a>
        @endif
    </div>
    <p class="text-stone-500 text-sm mt-1">Dibuat oleh {{ $pesanan->pembuat->name }} &middot; {{ $pesanan->created_at->format('d M Y, H:i') }}</p>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Detail pesanan --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-4">Detail Pesanan</h2>
            <dl class="grid sm:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                <div>
                    <dt class="text-stone-400">Nama Customer</dt>
                    <dd class="text-stone-900 font-medium mt-0.5">{{ $pesanan->nama_customer }}</dd>
                </div>
                <div>
                    <dt class="text-stone-400">Nomor HP</dt>
                    <dd class="text-stone-900 font-medium mt-0.5">{{ $pesanan->nomor_hp }}</dd>
                </div>
                <div>
                    <dt class="text-stone-400">Sumber Pesanan</dt>
                    <dd class="text-stone-900 font-medium mt-0.5">{{ $pesanan->sumber_pesanan }}</dd>
                </div>
                <div>
                    <dt class="text-stone-400">Deadline</dt>
                    <dd class="text-stone-900 font-medium mt-0.5">{{ $pesanan->deadline->format('d M Y') }}</dd>
                </div>
                <div>
                    <dt class="text-stone-400">Jenis Produk</dt>
                    <dd class="text-stone-900 font-medium mt-0.5">{{ $pesanan->produk->nama_produk }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-stone-400 mb-1">Ukuran</dt>
                    @php
                        $ukuranParts = explode(' x ', $pesanan->ukuran);
                        $panjang = $ukuranParts[0] ?? '-';
                        $lebar = $ukuranParts[1] ?? '-';
                        $tebal = $ukuranParts[2] ?? '-';
                    @endphp
                    <dd class="text-stone-900 font-medium mt-0.5 flex flex-col gap-0.5">
                        <span>Panjang : {{ $panjang !== '-' ? $panjang . ' cm' : '-' }}</span>
                        <span>Lebar : {{ $lebar !== '-' ? $lebar . ' cm' : '-' }}</span>
                        <span>Tebal : {{ $tebal !== '-' ? $tebal . ' cm' : '-' }}</span>
                    </dd>
                </div>
                <div>
                    <dt class="text-stone-400">Jumlah</dt>
                    <dd class="text-stone-900 font-medium mt-0.5">{{ $pesanan->jumlah }} pcs</dd>
                </div>
                <div>
                    <dt class="text-stone-400">Harga</dt>
                    <dd class="text-stone-900 font-medium mt-0.5">Rp {{ number_format($pesanan->harga, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-stone-400">Teknisi</dt>
                    <dd class="text-stone-900 font-medium mt-0.5">{{ $pesanan->teknisi->nama ?? '—' }}</dd>
                </div>
                
                {{-- DATA PEMBAYARAN (HANYA CIO MARKETING) --}}
                @if(auth()->user()->isMarketing())
                <div>
                    <dt class="text-stone-400">Status Pembayaran</dt>
                    <dd class="text-stone-900 font-medium mt-0.5">{{ $pesanan->status_pembayaran ?? 'Belum Lunas' }}</dd>
                </div>
                <div>
                    <dt class="text-stone-400">Nominal Dibayar</dt>
                    <dd class="text-stone-900 font-medium mt-0.5">Rp {{ number_format($pesanan->nominal_pembayaran, 0, ',', '.') }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-stone-400">Bukti Pembayaran / Kwitansi</dt>
                    <dd class="text-stone-900 font-medium mt-0.5">
                        @if($pesanan->bukti_pembayaran)
                            <a href="{{ asset($pesanan->bukti_pembayaran) }}" target="_blank" class="inline-flex items-center gap-2 text-brand-600 hover:underline">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                Lihat File Bukti
                            </a>
                        @else
                            <span class="text-stone-500">Tidak ada bukti pembayaran diupload</span>
                        @endif
                    </dd>
                </div>
                @endif

                @if($pesanan->spesifikasi)
                <div class="sm:col-span-2">
                    <dt class="text-stone-400">Spesifikasi</dt>
                    <dd class="text-stone-700 mt-0.5">{{ $pesanan->spesifikasi }}</dd>
                </div>
                @endif
                @if($pesanan->catatan)
                <div class="sm:col-span-2">
                    <dt class="text-stone-400">Catatan</dt>
                    <dd class="text-stone-700 mt-0.5">{{ $pesanan->catatan }}</dd>
                </div>
                @endif
            </dl>

            @if($pesanan->file_desain)
            <div class="mt-5 pt-5 border-t border-stone-100">
                <dt class="text-stone-400 text-sm mb-2">File Desain</dt>
                <a href="{{ asset($pesanan->file_desain) }}" target="_blank" class="inline-block">
                    <img src="{{ asset($pesanan->file_desain) }}" alt="Desain pesanan" class="max-w-xs rounded-xl border border-stone-200 hover:opacity-90 transition">
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- Aksi update status --}}
    <div class="space-y-6">
        
        {{-- 1. FORM UBAH STATUS (HANYA CIO PRODUCTION & STATUS MASIH PRODUKSI) --}}
        @if(auth()->user()->isProduction() && in_array($pesanan->status, ['queue', 'processing', 'delayed']))
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            @php
                // Cek apakah status yang tersimpan di DB saat ini adalah Diproses atau Tertunda
                $isCurrentlyProcessing = in_array($pesanan->status, ['processing', 'delayed']);
                
                // === LOGIKA AUTO-FILL BAHAN BAKU ===
                $ukuranParts = explode(' x ', $pesanan->ukuran);
                $p = floatval($ukuranParts[0] ?? 0);
                $l = floatval($ukuranParts[1] ?? 0);
                $j = floatval($pesanan->jumlah);

                // Siapkan map javascript untuk AlpineJS (agar tau nama & satuan saat dipilih manual)
                $bahanMap = $bahanBakuList->mapWithKeys(function($b) {
                    return [(string)$b->id => ['nama' => $b->nama, 'satuan' => $b->satuan]];
                })->toJson();

                $autoBahan = [];
                if ($p > 0 && $l > 0) {
                    $luas = ($p * $l) / 10000; // konversi cm2 ke m2
                    $keliling = (2 * ($p + $l)) / 100; // konversi cm ke m

                    $produkNama = strtolower($pesanan->produk->nama_produk);

                    // Cari bahan beserta nama & satuan berdasarkan nama persis di database
                    $findBahan = function($nama) use ($bahanBakuList) {
                        return $bahanBakuList->first(function($b) use ($nama) {
                            return strtolower($b->nama) === strtolower($nama);
                        });
                    };

                    // RUMUS OTOMATIS PER PRODUK (is_saved = false agar bisa dihapus saat masih antrian)
                    if (str_contains($produkNama, 'neon box')) {
                        if ($b = $findBahan('Acrylic 3mm')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, ceil($luas / 3 * $j)), 'is_saved' => false];
                        if ($b = $findBahan('Neon Flex LED')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, ceil($keliling * $j)), 'is_saved' => false];
                        if ($b = $findBahan('Power Supply')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, ceil($keliling * $j / 3)), 'is_saved' => false];
                        if ($b = $findBahan('Kabel Listrik')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, $j), 'is_saved' => false];
                    } elseif (str_contains($produkNama, 'neon flex')) {
                        if ($b = $findBahan('Neon Flex LED')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, ceil($keliling * $j)), 'is_saved' => false];
                        if ($b = $findBahan('Power Supply')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, ceil($keliling * $j / 3)), 'is_saved' => false];
                        if ($b = $findBahan('Kabel Listrik')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, $j), 'is_saved' => false];
                    } elseif (str_contains($produkNama, 'running text')) {
                        if ($b = $findBahan('Rangka Aluminium')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, ceil($keliling * $j)), 'is_saved' => false];
                        $ledMod = ceil($luas * 20) * $j;
                        if ($b = $findBahan('LED Module')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, $ledMod), 'is_saved' => false];
                        if ($b = $findBahan('Power Supply')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, ceil($ledMod / 50)), 'is_saved' => false];
                        if ($b = $findBahan('Kabel Listrik')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, $j), 'is_saved' => false];
                    } elseif (str_contains($produkNama, 'slimbox')) {
                        if ($b = $findBahan('Acrylic 3mm')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, ceil($luas / 3 * $j)), 'is_saved' => false];
                        if ($b = $findBahan('Rangka Aluminium')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, ceil($keliling * $j)), 'is_saved' => false];
                        $ledMod = ceil($luas * 100) * $j;
                        if ($b = $findBahan('LED Module')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, $ledMod), 'is_saved' => false];
                        if ($b = $findBahan('Power Supply')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, ceil($ledMod / 50)), 'is_saved' => false];
                    } elseif (str_contains($produkNama, 'backlight')) {
                        if ($b = $findBahan('Acrylic 3mm')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, ceil($luas / 3 * $j)), 'is_saved' => false];
                        $ledMod = ceil($luas * 100) * $j;
                        if ($b = $findBahan('LED Module')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, $ledMod), 'is_saved' => false];
                        if ($b = $findBahan('Power Supply')) $autoBahan[] = ['bahan_baku_id' => (string)$b->id, 'nama' => $b->nama, 'satuan' => $b->satuan, 'jumlah_pakai' => (string)max(1, ceil($ledMod / 50)), 'is_saved' => false];
                    }
                }

                // Tentukan data baris awal untuk AlpineJS
                if (old('bahan')) {
                    $initialRows = collect(old('bahan'))->map(function($row) use ($bahanBakuList, $pesanan) {
                        $b = $bahanBakuList->firstWhere('id', (int)($row['bahan_baku_id'] ?? 0));
                        return [
                            'bahan_baku_id' => $row['bahan_baku_id'],
                            'nama' => $b?->nama ?? '',
                            'satuan' => $b?->satuan ?? '',
                            'jumlah_pakai' => $row['jumlah_pakai'],
                            'is_saved' => $pesanan->pemakaianBahan->isNotEmpty() // Jika gagal validasi tapi sebelumnya sudah disimpan
                        ];
                    })->values()->toArray();
                } elseif ($pesanan->pemakaianBahan->isNotEmpty()) {
                    // Jika sudah pernah disimpan, ambil dari DB (is_saved = true agar tidak bisa dihapus)
                    $initialRows = $pesanan->pemakaianBahan->map(fn($pb) => ['bahan_baku_id' => (string)$pb->bahan_baku_id, 'nama' => $pb->bahanBaku->nama, 'satuan' => $pb->bahanBaku->satuan, 'jumlah_pakai' => (string)$pb->jumlah_pakai, 'is_saved' => true])->toArray();
                } elseif (count($autoBahan) > 0) {
                    $initialRows = $autoBahan;
                } else {
                    $initialRows = [['bahan_baku_id' => '', 'nama' => '', 'satuan' => '', 'jumlah_pakai' => '', 'is_saved' => false]];
                }
            @endphp
            <form method="POST" action="{{ route('pesanan.update-status', $pesanan) }}" x-data="{ rows: {{ json_encode($initialRows) }}, bahanMap: {{ $bahanMap }}, selectedStatus: '{{ old('status', $pesanan->status) }}', isProcessing: {{ json_encode($isCurrentlyProcessing) }} }">
                @csrf
                @method('PUT')
                <h2 class="font-semibold text-stone-900 mb-4">Update Produksi</h2>

                {{-- DROPDOWN NAMA TEKNISI --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Nama Teknisi</label>
                    <select name="teknisi_id" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('teknisi_id') border-red-400 @enderror" @disabled($isCurrentlyProcessing)>
                        <option value="" disabled selected>Pilih teknisi</option>
                        @foreach($teknisiList as $t)
                            <option value="{{ $t->id }}" @selected(old('teknisi_id', $pesanan->teknisi_id) == $t->id)>{{ $t->nama }}</option>
                        @endforeach
                    </select>
                    @error('teknisi_id') <p class="text-xs text-red-600 mt-1">Harap pilih teknisi.</p> @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Status Pesanan</label>
                    <select name="status" x-model="selectedStatus" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                        @foreach($statusOptions as $value => $label)
                            @if(in_array($value, ['queue', 'processing', 'delayed', 'completed']))
                                @php
                                    $isDisabled = false;
                                    if ($value === $pesanan->status) {
                                        $isDisabled = true;
                                    } elseif ($pesanan->status === 'queue' && $value === 'completed') {
                                        $isDisabled = true;
                                    } elseif (in_array($pesanan->status, ['processing', 'delayed']) && $value === 'queue') {
                                        $isDisabled = true;
                                    }
                                @endphp
                                <option value="{{ $value }}" @selected(old('status', $pesanan->status) === $value) @disabled($isDisabled)>{{ $label }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                {{-- INPUT TANGGAL MULAI PRODUKSI --}}
                <div x-show="selectedStatus === 'processing' || selectedStatus === 'delayed' || selectedStatus === 'completed'" class="mb-4">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Tanggal Mulai Produksi</label>
                    <input type="date" name="tanggal_diproses" value="{{ old('tanggal_diproses', $pesanan->tanggal_diproses ? $pesanan->tanggal_diproses->format('Y-m-d') : '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('tanggal_diproses') border-red-400 @enderror" @disabled($isCurrentlyProcessing)>
                    @error('tanggal_diproses') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- INPUT TANGGAL SELESAI PRODUKSI --}}
                <div x-show="selectedStatus === 'completed'" class="mb-4">
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">Tanggal Selesai Produksi</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $pesanan->tanggal_selesai ? $pesanan->tanggal_selesai->format('Y-m-d') : '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition @error('tanggal_selesai') border-red-400 @enderror">
                    @error('tanggal_selesai') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- 4 CHECKBOX QC (HANYA MUNCUL JIKA DB SUDAH DIPROSES DAN DIPILIH SELESAI) --}}
                <div x-show="selectedStatus === 'completed' && isProcessing" class="mb-5">
                    <label class="block text-sm font-medium text-stone-700 mb-2">Quality Control (QC)</label>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="qc_desain" id="qc_desain" value="1" class="rounded border-stone-300 text-brand-600 focus:ring-brand-500" @checked(old('qc_desain', $pesanan->qc_desain))>
                            <label for="qc_desain" class="text-sm text-stone-700">QC Desain & Ukuran</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="qc_konstruksi" id="qc_konstruksi" value="1" class="rounded border-stone-300 text-brand-600 focus:ring-brand-500" @checked(old('qc_konstruksi', $pesanan->qc_konstruksi))>
                            <label for="qc_konstruksi" class="text-sm text-stone-700">QC Pengerjaan/Konstruksi</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="qc_kelistrikan" id="qc_kelistrikan" value="1" class="rounded border-stone-300 text-brand-600 focus:ring-brand-500" @checked(old('qc_kelistrikan', $pesanan->qc_kelistrikan))>
                            <label for="qc_kelistrikan" class="text-sm text-stone-700">QC Kelistrikan</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="qc_ketahanan" id="qc_ketahanan" value="1" class="rounded border-stone-300 text-brand-600 focus:ring-brand-500" @checked(old('qc_ketahanan', $pesanan->qc_ketahanan))>
                            <label for="qc_ketahanan" class="text-sm text-stone-700">QC Ketahanan/Outdoor</label>
                        </div>
                    </div>
                    {{-- PESAN ERROR QC DI BAWAH INI --}}
                    @if ($errors->has('qc_error'))
                        <p class="text-xs text-red-600 mt-2">{{ $errors->first('qc_error') }}</p>
                    @endif
                </div>

                <div class="mb-2 flex items-center justify-between">
                    <label class="block text-sm font-medium text-stone-700">Pemakaian Bahan Baku</label>
                </div>
                <p class="text-xs text-stone-400 mb-3">Bahan baku terisi otomatis berdasarkan perhitungan standar. Jumlah bisa diedit sesuai kebutuhan lapangan.</p>

                @error('bahan') <p class="text-xs text-red-600 mb-3">{{ $message }}</p> @enderror

                <template x-for="(row, index) in rows" :key="index">
                    <div class="flex items-center gap-2 mb-2">
                        {{-- Hidden input untuk menyimpan ID bahan baku --}}
                        <input type="hidden" :name="'bahan[' + index + '][bahan_baku_id]'" x-model="row.bahan_baku_id">
                        
                        <div class="flex-1">
                            {{-- Jika bahan sudah ada (auto-fill atau dari DB), tampilkan sebagai teks --}}
                            <template x-if="row.bahan_baku_id">
                                <div class="px-3 py-2 rounded-lg border border-stone-200 text-sm bg-stone-50 text-stone-700">
                                    <span x-text="row.nama || bahanMap[row.bahan_baku_id]?.nama"></span>
                                </div>
                            </template>
                            {{-- Jika bahan masih kosong (klik tambah manual), tampilkan dropdown --}}
                            <template x-if="!row.bahan_baku_id">
                                <select @change="row.nama = bahanMap[$event.target.value].nama; row.satuan = bahanMap[$event.target.value].satuan" x-model="row.bahan_baku_id" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                                    <option value="">Pilih bahan...</option>
                                    @foreach($bahanBakuList as $bahan)
                                    <option value="{{ $bahan->id }}">{{ $bahan->nama }}</option>
                                    @endforeach
                                </select>
                            </template>
                        </div>

                        <div class="flex items-center gap-1">
                            <input type="number" step="1" min="1" :name="'bahan[' + index + '][jumlah_pakai]'" x-model="row.jumlah_pakai" placeholder="Jumlah" class="w-20 px-3 py-2 rounded-lg border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                            <span class="text-xs text-stone-500 w-16" x-text="row.satuan || (bahanMap[row.bahan_baku_id]?.satuan || '')"></span>
                        </div>

                        {{-- Tombol Hapus: Hanya muncul jika baris lebih dari 1 DAN bahan tersebut BELUM tersimpan (is_saved == false) --}}
                        <button type="button" @click="rows.splice(index, 1)" class="px-2.5 text-stone-400 hover:text-red-600 transition" x-show="rows.length > 1 && !row.is_saved">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </template>
                <button type="button" @click="rows.push({ bahan_baku_id: '', nama: '', satuan: '', jumlah_pakai: '', is_saved: false })" class="text-sm font-medium text-brand-600 hover:text-brand-700 mb-5">+ Tambah bahan</button>

                <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm py-2.5 rounded-xl transition shadow-sm shadow-brand-500/20">Update</button>
            </form>
        </div>

        @if($pesanan->pemakaianBahan->isNotEmpty())
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-4">Informasi Pemakaian Bahan Baku</h2>
            <div class="divide-y divide-stone-100">
                @foreach($pesanan->pemakaianBahan as $pakai)
                <div class="py-2.5 flex items-center justify-between text-sm">
                    <span class="text-stone-700">{{ $pakai->bahanBaku->nama }}</span>
                    <span class="font-medium text-stone-900">{{ floatval($pakai->jumlah_pakai) }} {{ $pakai->bahanBaku->satuan }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @elseif(auth()->user()->isProduction() && $pesanan->status === 'completed')
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-2">Update Produksi</h2>
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4">
                <p class="text-sm text-gray-600">Status pesanan sudah <span class="font-semibold">Selesai Produksi</span>. Menunggu CIO Marketing untuk mengubah status menjadi Diterima Pelanggan.</p>
            </div>
            
            {{-- TAMPILKAN TANGGAL PRODUKSI SAAT SUDAH SELESAI --}}
            <div class="mt-4 mb-4 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-stone-400">Tgl Mulai Produksi</p>
                    <p class="text-sm font-medium text-stone-900">{{ $pesanan->tanggal_diproses ? $pesanan->tanggal_diproses->format('d M Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-stone-400">Tgl Selesai Produksi</p>
                    <p class="text-sm font-medium text-stone-900">{{ $pesanan->tanggal_selesai ? $pesanan->tanggal_selesai->format('d M Y') : '-' }}</p>
                </div>
            </div>

            {{-- TAMPILKAN QC SAAT SUDAH SELESAI (READ ONLY) --}}
            <div class="mt-4">
                <label class="block text-sm font-medium text-stone-700 mb-2">Quality Control (QC) Result</label>
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" disabled @checked($pesanan->qc_desain) class="rounded border-stone-300 text-brand-600">
                        <span class="text-sm text-stone-700">QC Desain & Ukuran</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" disabled @checked($pesanan->qc_konstruksi) class="rounded border-stone-300 text-brand-600">
                        <span class="text-sm text-stone-700">QC Pengerjaan/Konstruksi</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" disabled @checked($pesanan->qc_kelistrikan) class="rounded border-stone-300 text-brand-600">
                        <span class="text-sm text-stone-700">QC Kelistrikan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" disabled @checked($pesanan->qc_ketahanan) class="rounded border-stone-300 text-brand-600">
                        <span class="text-sm text-stone-700">QC Ketahanan/Outdoor</span>
                    </div>
                </div>
            </div>
        </div>
        
        @if($pesanan->pemakaianBahan->isNotEmpty())
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-4">Informasi Pemakaian Bahan Baku</h2>
            <div class="divide-y divide-stone-100">
                @foreach($pesanan->pemakaianBahan as $pakai)
                <div class="py-2.5 flex items-center justify-between text-sm">
                    <span class="text-stone-700">{{ $pakai->bahanBaku->nama }}</span>
                    <span class="font-medium text-stone-900">{{ floatval($pakai->jumlah_pakai) }} {{ $pakai->bahanBaku->satuan }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @elseif(auth()->user()->isMarketing() && $pesanan->status === 'completed')
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-4">Update Status</h2>
            <form method="POST" action="{{ route('pesanan.update-status', $pesanan) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="diterima">
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-green-900">Produksi telah selesai.</p>
                        <p class="text-sm text-green-700">Klik tombol di bawah jika barang sudah diterima oleh pelanggan.</p>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        Diterima Pelanggan
                    </button>
                </div>
            </form>
        </div>

        @elseif(auth()->user()->isMarketing() && in_array($pesanan->status, ['queue', 'processing', 'delayed']))
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-2">Update Status</h2>
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                @if($pesanan->status === 'queue')
                <p class="text-sm text-gray-600">Pesanan masih dalam antrian. Tunggu hingga CIO Production memproses pesanan ini.</p>
                @elseif($pesanan->status === 'processing')
                <p class="text-sm text-gray-600">Pesanan sedang dalam proses produksi. Tunggu hingga CIO Production menyelesaikan produksinya.</p>
                @elseif($pesanan->status === 'delayed')
                <p class="text-sm text-gray-600">Proses produksi pesanan tertunda. Silakan koordinasi dengan CIO Production terkait kendala yang terjadi.</p>
                @endif
            </div>
        </div>
        @endif

        @if($pesanan->permintaanBahan->isNotEmpty())
        <div class="bg-white border border-stone-200 rounded-2xl p-6">
            <h2 class="font-semibold text-stone-900 mb-4">Permintaan Bahan Terkait</h2>
            <div class="divide-y divide-stone-100">
                @foreach($pesanan->permintaanBahan as $p)
                <a href="{{ route('pengajuan.show', $p) }}" class="py-2.5 flex items-center justify-between text-sm hover:bg-stone-50 -mx-2 px-2 rounded-lg">
                    <span class="text-stone-700">{{ $p->nomor_permintaan }}</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset {{ $p->statusBadgeClass() }}">{{ $p->statusLabel() }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection