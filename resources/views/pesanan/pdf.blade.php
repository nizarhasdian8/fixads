<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Pesanan</title>
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
    <h2>Laporan Data Pesanan
        @if($bulan) <br> Bulan {{ $namaBulan[(int)$bulan] }} @endif
        @if($tahun) Tahun {{ $tahun }} @endif
        @if($status) (Status: {{ ucfirst($status) }}) @endif
    </h2>

    <table>
        <thead>
            <tr>
                <th>No. Invoice</th>
                <th>Tanggal Pesanan</th>
                <th>Nama Customer</th>
                <th>Produk</th>
                <th class="text-center">Jumlah</th>
                <th class="text-right">Harga</th>
                <th>Deadline</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanan as $item)
            <tr>
                <td>{{ $item->nomor_invoice }}</td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                <td>{{ $item->nama_customer }}</td>
                <td>{{ $item->produk->nama_produk }}</td>
                <td class="text-center">{{ $item->jumlah }}</td>
                <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->deadline)->format('d M Y') }}</td>
                <td>{{ $item->statusLabel() }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data pesanan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-20">
        <p>Dicetak pada: {{ date('d M Y, H:i') }}</p>
    </div>
</body>
</html>