<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\BahanMasuk;
use App\Models\PermintaanBahan;
use App\Models\Pesanan;
use App\Models\PesananBahanBaku;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

                    if ($user->isMarketing()) {
            // Hitung KPI untuk CIO Marketing (Berdasarkan hari ini)
            $pesananMasukHariIni = Pesanan::whereDate('created_at', today())->count();
            
            // Hitung yang berubah status menjadi completed/diterima HARI INI
            $siapDiambil = Pesanan::where('status', 'completed')
                ->whereDate('updated_at', today())
                ->count();
                
            $diterimaPelanggan = Pesanan::where('status', 'diterima')
                ->whereDate('updated_at', today())
                ->count();

            return view('dashboard.marketing', [
                'pesananMasukHariIni' => $pesananMasukHariIni,
                'siapDiambil' => $siapDiambil,
                'diterimaPelanggan' => $diterimaPelanggan,
                
                // Data lama yang masih dipakai di view
                'totalPesanan' => Pesanan::count(),
                'pesananTerbaru' => Pesanan::with(['produk', 'teknisi'])
                    ->whereDate('created_at', today())
                    ->latest()
                    ->take(5)
                    ->get(),
                'permintaanPending' => PermintaanBahan::where('status', 'pending')->count(),
            ]);
        }

        // Hitung KPI untuk CIO Production
        $bahanMenipis = BahanBaku::whereColumn('stok', '<=', 'stok_minimum')->count();
        $bahanMasukHariIni = BahanMasuk::whereDate('tanggal', today())->count();
        $bahanKeluarHariIni = PesananBahanBaku::whereDate('created_at', today())->count();

        return view('dashboard.production', [
            'bahanMenipis' => $bahanMenipis,
            'bahanMasukHariIni' => $bahanMasukHariIni,
            'bahanKeluarHariIni' => $bahanKeluarHariIni,
            
            // Data lama yang masih dipakai di view
            'totalPermintaan' => PermintaanBahan::count(),
            'bahanHampirHabis' => BahanBaku::whereColumn('stok', '<=', 'stok_minimum')->get(),
            'pesananAntrian' => Pesanan::with(['produk', 'teknisi'])
                ->whereIn('status', ['queue', 'delayed'])
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}