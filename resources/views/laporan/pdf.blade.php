<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian Bahan Baku</title>
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
            </td>
            <td style="width: 40%; text-align: center;">
                <h2 style="margin: 0; font-size: 20px; font-weight: bold;">Fix Advertising</h2>
                <p style="margin: 0; font-size: 11px; color: #555;">
                    Ruko Cipta Pesona Jl. Raya Cipamokolan No. 12,<br>
                    Cipamokolan, Kecamatan Rancasari, Kota Bandung, Jawa Barat
                </p>
            </td>
            <td style="width: 30%;"></td>
        </tr>
    </table>
    <div class="header-line"></div>

    <h1>Laporan Harian Bahan Baku</h1>
    <h2>Tanggal: {{ date('d M Y', strtotime($tanggal)) }}</h2>

    <h3>Bahan Keluar (Pemakaian Produksi)</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Bahan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bahanKeluar as $item)
            <tr>
                <td>{{ $item->bahanBaku->nama }}</td>
                <td>{{ floatval($item->jumlah_pakai) }} {{ $item->bahanBaku->satuan }}</td>
            </tr>
            @empty
            <tr><td colspan="2" class="text-center">Tidak ada bahan keluar pada tanggal ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h3 style="margin-top: 30px;">Bahan Masuk (Penerimaan Supplier)</h3>
    <table>
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Nama Bahan</th>
                <th>Jumlah</th>
                <th>Nama Supplier</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bahanMasuk as $item)
            <tr>
                <td>{{ $item->nomor_transaksi }}</td>
                <td>{{ $item->bahanBaku->nama }}</td>
                <td>{{ floatval($item->jumlah) }} {{ $item->bahanBaku->satuan }}</td>
                <td>{{ $item->nama_supplier ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Tidak ada bahan masuk pada tanggal ini.</td></tr>
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