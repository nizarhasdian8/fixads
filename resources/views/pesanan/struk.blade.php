<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan</title>
    <style>
        body {
            font-family: monospace; /* Font khas struk kasir */
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 5px;
            width: 100%;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .dashed-line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 2px 0;
            vertical-align: top;
        }
        .logo-img {
            width: 60px;
            height: auto;
            display: block;
            margin: 0 auto 5px auto;
        }
    </style>
</head>
<body>
    {{-- KOP SURAT --}}
    <div class="text-center">
        @if(file_exists(public_path('assets/img/logo.png')))
            <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo" class="logo-img">
        @endif
        <h2 style="margin: 0; font-size: 14px;">Fix Advertising</h2>
        <p style="margin: 0; font-size: 10px;">
            Ruko Cipta Pesona Jl. Raya Cipamokolan No. 12,<br>
            Cipamokolan, Kecamatan Rancasari, Kota Bandung
        </p>
    </div>

    <div class="dashed-line"></div>

    {{-- TANGGAL CETAK --}}
    <table>
        <tr>
            <td>Tanggal</td>
            <td class="text-right">{{ date('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <td>Invoice</td>
            <td class="text-right">{{ $pesanan->nomor_invoice }}</td>
        </tr>
    </table>

    <div class="dashed-line"></div>

    {{-- DETAIL PESANAN --}}
    <table>
        <tr>
            <td>Nama</td>
            <td class="text-right">{{ $pesanan->nama_customer }}</td>
        </tr>
        <tr>
            <td>Produk</td>
            <td class="text-right">{{ $pesanan->produk->nama_produk }}</td>
        </tr>
        <tr>
            <td>Jumlah</td>
            <td class="text-right">{{ $pesanan->jumlah }} pcs</td>
        </tr>
        <tr>
            <td>Status</td>
            <td class="text-right">{{ $pesanan->status_pembayaran }}</td>
        </tr>
    </table>

    <div class="dashed-line"></div>

    {{-- DETAIL HARGA & PEMBAYARAN --}}
    @php
        $total = $pesanan->harga;
        $bayar = $pesanan->nominal_pembayaran ?? 0;
        $sisa = $total - $bayar;
    @endphp
    <table>
        <tr>
            <td>Harga Produk</td>
            <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Bayar</td>
            <td class="text-right" style="font-weight: bold;">Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
        
        @if($pesanan->status_pembayaran === 'DP')
        <tr>
            <td>Bayar (DP)</td>
            <td class="text-right">Rp {{ number_format($bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Sisa Bayar</td>
            <td class="text-right" style="font-weight: bold;">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
        </tr>
        @elseif($pesanan->status_pembayaran === 'Lunas')
        <tr>
            <td>Bayar</td>
            <td class="text-right">Rp {{ number_format($bayar, 0, ',', '.') }}</td>
        </tr>
        @endif
    </table>

    <div class="dashed-line"></div>

    {{-- FOOTER --}}
    <div class="text-center" style="margin-top: 10px;">
        <p style="margin: 0;">Terimakasih</p>
        <p style="margin: 0;">atas kepercayaan anda.</p>
    </div>
</body>
</html>