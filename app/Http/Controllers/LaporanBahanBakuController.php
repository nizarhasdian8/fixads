<?php

namespace App\Http\Controllers;

use App\Models\BahanMasuk;
use App\Models\PesananBahanBaku;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanBahanBakuController extends Controller
{
    public function harian(Request $request): View
    {
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));

        // 1. Data Bahan Keluar (Pemakaian BOM) di tanggal tersebut
        $bahanKeluar = PesananBahanBaku::with(['bahanBaku', 'pesanan'])
            ->whereDate('created_at', $tanggal)
            ->get();

        // 2. Data Bahan Masuk (dari supplier) di tanggal tersebut
        $bahanMasuk = BahanMasuk::with(['bahanBaku', 'pencatat'])
            ->whereDate('tanggal', $tanggal)
            ->get();

        // 3. Total ringkasan
        $totalKeluar = $bahanKeluar->count();
        $totalMasuk = $bahanMasuk->count();

        return view('laporan.harian', [
            'tanggal' => $tanggal,
            'bahanKeluar' => $bahanKeluar,
            'bahanMasuk' => $bahanMasuk,
            'totalKeluar' => $totalKeluar,
            'totalMasuk' => $totalMasuk,
        ]);
    }

    public function downloadPdf(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));

        $bahanKeluar = PesananBahanBaku::with(['bahanBaku', 'pesanan'])
            ->whereDate('created_at', $tanggal)
            ->get();

        $bahanMasuk = BahanMasuk::with(['bahanBaku', 'pencatat'])
            ->whereDate('tanggal', $tanggal)
            ->get();

        // Hitung total untuk dikirim ke PDF
        $totalKeluar = $bahanKeluar->count();
        $totalMasuk = $bahanMasuk->count();

        $pdf = Pdf::loadView('laporan.pdf', compact('tanggal', 'bahanKeluar', 'bahanMasuk', 'totalKeluar', 'totalMasuk'));
        
        return $pdf->download('laporan-bahan-baku-' . $tanggal . '.pdf');
    }

    // ================== LAPORAN BULANAN ==================
    
    public function bulanan(Request $request): View
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // 1. Data Bahan Keluar (BOM) di bulan/tahun tersebut
        $bahanKeluar = PesananBahanBaku::with(['bahanBaku', 'pesanan'])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->get();

        // 2. Data Bahan Masuk di bulan/tahun tersebut
        $bahanMasuk = BahanMasuk::with(['bahanBaku', 'pencatat'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $totalKeluar = $bahanKeluar->count();
        $totalMasuk = $bahanMasuk->count();

        // Ambil list tahun yang ada di database
        $tahunOptions = PesananBahanBaku::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('laporan.bulanan', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'namaBulan' => $namaBulan,
            'bahanKeluar' => $bahanKeluar,
            'bahanMasuk' => $bahanMasuk,
            'totalKeluar' => $totalKeluar,
            'totalMasuk' => $totalMasuk,
            'tahunOptions' => $tahunOptions,
        ]);
    }

    public function downloadPdfBulanan(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $bahanKeluar = PesananBahanBaku::with(['bahanBaku', 'pesanan'])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->get();

        $bahanMasuk = BahanMasuk::with(['bahanBaku', 'pencatat'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $totalKeluar = $bahanKeluar->count();
        $totalMasuk = $bahanMasuk->count();

        $pdf = Pdf::loadView('laporan.pdf_bulanan', compact('bulan', 'tahun', 'namaBulan', 'bahanKeluar', 'bahanMasuk', 'totalKeluar', 'totalMasuk'));
        
        return $pdf->download('laporan-bulanan-bahan-baku-' . $namaBulan[(int)$bulan] . '-' . $tahun . '.pdf');
    }
}