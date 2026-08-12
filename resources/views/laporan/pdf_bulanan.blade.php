<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan Bahan Baku</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { text-align: center; margin-bottom: 5px; font-size: 18px; }
        h2 { text-align: center; margin-top: 0; font-size: 14px; font-weight: normal; margin-bottom: 20px;}
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mt-20 { margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Fix Advertising</h1>
    <h2>Laporan Bulanan Bahan Baku - {{ $namaBulan[(int)$bulan] }} {{ $tahun }}</h2>

    <div class="summary" style="display: flex; justify-content: space-around; margin-bottom: 20px;">
        <div style="border: 1px solid #ccc; padding: 10px 20px; text-align: center;">
            <strong>Total Bahan Keluar</strong><br>
            {{ $totalKeluar }} Transaksi
        </div>
        <div style="border: 1px solid #ccc; padding: 10px 20px; text-align: center;">
            <strong>Total Bahan Masuk</strong><br>
            {{ $totalMasuk }} Transaksi
        </div>
    </div>

    <h3>Bahan Keluar (Pemakaian Produksi)</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Bahan</th>
                <th>Jumlah</th>
                <th>Untuk Pesanan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bahanKeluar as $item)
            <tr>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                <td>{{ $item->bahanBaku->nama }}</td>
                <td>{{ rtrim(rtrim($item->jumlah_pakai, '0'), '.') }} {{ $item->bahanBaku->satuan }}</td>
                <td>{{ $item->pesanan->nomor_invoice }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada bahan keluar pada bulan ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h3 style="margin-top: 30px;">Bahan Masuk (Penerimaan Supplier)</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kode Transaksi</th>
                <th>Nama Bahan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bahanMasuk as $item)
            <tr>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                <td>{{ $item->nomor_transaksi }}</td>
                <td>{{ $item->bahanBaku->nama }}</td>
                <td>{{ rtrim(rtrim($item->jumlah, '0'), '.') }} {{ $item->bahanBaku->satuan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada bahan masuk pada bulan ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-20">
        <p>Dicetak pada: {{ date('d M Y, H:i') }}</p>
    </div>
</body>
</html>