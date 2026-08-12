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
        .mt-20 { margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Fix Advertising</h1>
    <h2>Laporan Permintaan Bahan Baku
        @if($bulan) <br> Bulan {{ $namaBulan[(int)$bulan] }} @endif
        @if($tahun) Tahun {{ $tahun }} @endif
        @if($status) (Status: {{ ucfirst($status) }}) @endif
    </h2>

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
                <td>{{ $item->jumlah }} {{ $item->bahanBaku->satuan }}</td>
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

    <div class="mt-20">
        <p>Dicetak pada: {{ date('d M Y, H:i') }}</p>
    </div>
</body>
</html>