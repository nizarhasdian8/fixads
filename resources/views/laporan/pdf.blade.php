<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bahan Baku - {{ $tanggal }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { text-align: center; margin-bottom: 5px; font-size: 18px; }
        h2 { font-size: 14px; border-bottom: 2px solid #333; padding-bottom: 5px; margin-top: 30px; }
        .date { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .summary { display: flex; justify-content: space-around; margin-bottom: 20px; }
        .summary-box { border: 1px solid #ccc; padding: 10px 20px; text-align: center; }
    </style>
</head>
<body>
    <h1>Fix Advertising</h1>
    <p class="date">Laporan Harian Bahan Baku - {{ date('d M Y', strtotime($tanggal)) }}</p>

    <div class="summary">
        <div class="summary-box">
            <strong>Bahan Keluar</strong><br>
            {{ $totalKeluar }} Transaksi
        </div>
        <div class="summary-box">
            <strong>Bahan Masuk</strong><br>
            {{ $totalMasuk }} Transaksi
        </div>
    </div>

    <h2>Bahan Keluar (Pemakaian Produksi)</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Bahan</th>
                <th>Jumlah</th>
                <th>Untuk Pesanan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bahanKeluar as $item)
            <tr>
                <td>{{ $item->bahanBaku->nama }}</td>
                <td>{{ rtrim(rtrim($item->jumlah_pakai, '0'), '.') }} {{ $item->bahanBaku->satuan }}</td>
                <td>{{ $item->pesanan->nomor_invoice }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center;">Tidak ada bahan keluar</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Bahan Masuk (Penerimaan Supplier)</h2>
    <table>
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Nama Bahan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bahanMasuk as $item)
            <tr>
                <td>{{ $item->nomor_transaksi }}</td>
                <td>{{ $item->bahanBaku->nama }}</td>
                <td>{{ $item->jumlah }} {{ $item->bahanBaku->satuan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center;">Tidak ada bahan masuk</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>