<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Permintaan Bahan Baku</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { text-align: center; margin-bottom: 5px; font-size: 18px; }
        h2 { text-align: center; margin-top: 0; font-size: 14px; font-weight: normal; margin-bottom: 20px;}
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .text-center { text-align: center; }
        .header-table { width: 100%; border: none; margin-bottom: 0; }
        .header-table td { border: none; padding: 0; vertical-align: middle; }
        .header-line { border-bottom: 3px solid #000; margin-top: 10px; margin-bottom: 20px; }
        .signature-box { margin-top: 50px; width: 100%; text-align: right; }
        .signature-space { height: 80px; }
    </style>
</head>
<body>
    {{-- KOP SURAT --}}
    <table class="header-table">
        <tr>
            <td style="width: 30%; text-align: left;">
                <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo" style="width: 90px; height: auto;">
            <td style="width: 40%; text-align: center;">
                <h2 style="margin: 0; font-size: 20px; font-weight: bold;">Fix Advertising</h2>
                <p style="margin: 0; font-size: 11px; color: #555;">
                    Ruko Cipta Pesona Jl. Raya Cipamokolan No. 12,<br>
                    Cipamokolan, Kecamatan Rancasari, Kota Bandung, Jawa Barat
                </p>
            </td>
            <td style="width: 30%;">
                {{-- KOLOM KOSONG UNTUK KESEIMBANGGAN --}}
            </td>
        </tr>
    </table>
    <div class="header-line"></div>

    {{-- JUDUL LAPORAN --}}
    <h1>Laporan Permintaan Bahan Baku</h1>
    <h2>
        @if($bulan) Bulan {{ $namaBulan[(int)$bulan] }} @endif
        @if($tahun) Tahun {{ $tahun }} @endif
        @if($status) (Status: {{ ucfirst($status) }}) @endif
    </h2>

    {{-- TABEL DATA --}}
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Permintaan</th>
                <th>Tanggal Pengajuan</th>
                <th>Bahan Baku</th>
                <th>Jumlah</th>
                <th>Diajukan Oleh</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permintaan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nomor_permintaan }}</td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                <td>{{ $item->bahanBaku->nama }}</td>
                <td>{{ floatval($item->jumlah) }} {{ $item->bahanBaku->satuan }}</td>
                <td>{{ $item->pengaju->name }}</td>
                <td>{{ $item->statusLabel() }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data permintaan bahan baku pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <div class="signature-box">
        <p>Bandung, {{ date('d M Y') }}</p>
        <p>Hormat Kami,</p>
        <div class="signature-space"></div>
        <p style="text-decoration: underline; font-weight: bold;">(Nama CIO Marketing)</p>
        <p>CIO Marketing</p>
    </div>
</body>
</html>