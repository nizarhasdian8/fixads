<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Teknisi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class PesananController extends Controller
{
    public function index(Request $request): View
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $pesanan = Pesanan::query()
            ->with('produk')
            ->when($request->search, function ($query, $search) {
                $query->where('nama_customer', 'like', "%{$search}%")
                    ->orWhere('nomor_invoice', 'like', "%{$search}%");
            })
            ->when($bulan, function ($query, $bulan) {
                $query->whereMonth('created_at', $bulan);
            })
            ->when($tahun, function ($query, $tahun) {
                $query->whereYear('created_at', $tahun);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $tahunOptions = Pesanan::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('pesanan.index', [
            'pesanan' => $pesanan,
            'search' => $request->search,
            'statusFilter' => $request->status,
            'statusOptions' => Pesanan::statusOptions(),
            'bulanFilter' => $bulan,
            'tahunFilter' => $tahun,
            'namaBulan' => $namaBulan,
            'tahunOptions' => $tahunOptions,
        ]);
    }

    public function create(): View
    {
        return view('pesanan.create', [
            'produkList' => Produk::where('status_aktif', true)->orderBy('nama_produk')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_customer' => ['required', 'string', 'max:255'],
            'nomor_hp' => ['required', 'string', 'min:11', 'max:15'],
            'sumber_pesanan' => ['required', 'string', 'max:100'],
            'produk_id' => ['required', 'exists:produk,id'],
            'panjang' => ['required', 'string', 'max:50'], 
            'lebar' => ['required', 'string', 'max:50'],   
            'tebal' => ['required', 'string', 'max:50'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'harga' => ['required', 'numeric', 'min:0'],
            'spesifikasi' => ['required', 'string'],
            'file_desain' => ['required', 'image', 'max:5120'],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
            'catatan' => ['nullable', 'string'],
            'status_pembayaran' => ['required', 'in:DP,Lunas'],  
            'bukti_pembayaran' => ['required', 'image', 'max:2048'], 
            'nominal_pembayaran' => ['nullable', 'numeric', 'min:0'],
        ], [
            'nomor_hp.required' => 'Harap isi dengan benar.',
            'nomor_hp.min' => 'Harap isi dengan benar.',
            'nomor_hp.max' => 'Harap isi dengan benar.',
            'harga.required' => 'Harap isi dengan benar.',
            'harga.numeric' => 'Harap isi dengan benar.',
            'bukti_pembayaran.required' => 'Harap upload bukti pembayaran.', 
            'required' => 'Kolom :attribute wajib diisi.',
            'min' => 'Kolom :attribute minimal harus :min karakter.',
            'max' => 'Kolom :attribute maksimal harus :max karakter.',
            'integer' => 'Kolom :attribute harus berupa angka bulat.',
            'image' => 'File :attribute harus berupa gambar.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'after_or_equal' => 'Kolom :attribute harus tanggal hari ini atau setelahnya.',
            'exists' => 'Produk yang dipilih tidak valid.',
        ], [
            'nama_customer' => 'Nama Customer',
            'sumber_pesanan' => 'Sumber Pesanan',
            'produk_id' => 'Jenis Produk',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'tebal' => 'Tebal',
            'jumlah' => 'Jumlah',
            'spesifikasi' => 'Spesifikasi',
            'file_desain' => 'File Desain',
            'deadline' => 'Deadline',
            'catatan' => 'Catatan',
            'status_pembayaran' => 'Status Pembayaran',
            'bukti_pembayaran' => 'Bukti Pembayaran',
            'nominal_pembayaran' => 'Nominal Pembayaran',
        ]);

        $data['ukuran'] = $data['panjang'] . ' x ' . $data['lebar'] . ' x ' . $data['tebal'];
        unset($data['panjang'], $data['lebar'], $data['tebal']);

        if ($request->hasFile('bukti_pembayaran')) {
            $fileKwitansi = $request->file('bukti_pembayaran');
            $filenameKwitansi = $fileKwitansi->hashName();
            $fileKwitansi->move(public_path('kwitansi-pembayaran'), $filenameKwitansi);
            $data['bukti_pembayaran'] = 'kwitansi-pembayaran/' . $filenameKwitansi;
        }

        if ($request->hasFile('file_desain')) {
            $file = $request->file('file_desain');
            $filename = $file->hashName();
            $file->move(public_path('desain-pesanan'), $filename);
            $data['file_desain'] = 'desain-pesanan/' . $filename;
        }

        if ($data['status_pembayaran'] === 'Belum Lunas') {
            $data['nominal_pembayaran'] = 0;
        }

        $data['nomor_invoice'] = $this->generateNomorInvoice();
        $data['status'] = 'queue';
        $data['created_by'] = $request->user()->id;

        $pesanan = Pesanan::create($data);

        return redirect()->route('pesanan.show', $pesanan)->with('success', "Pesanan {$pesanan->nomor_invoice} berhasil dibuat.");
    }

    public function show(Pesanan $pesanan): View
    {
        $pesanan->load(['produk', 'pembuat', 'pemakaianBahan.bahanBaku', 'permintaanBahan.bahanBaku', 'teknisi']);

        return view('pesanan.show', [
            'pesanan' => $pesanan,
            'bahanBakuList' => BahanBaku::orderBy('nama')->get(),
            'statusOptions' => Pesanan::statusOptions(),
            'teknisiList' => Teknisi::orderBy('nama')->get(),
        ]);
    }

    public function updateStatus(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $user = $request->user();
        $currentStatus = $pesanan->status;
        $newStatus = $request->status;

        if ($newStatus === 'diterima') {
            if ($user->role !== 'cio_marketing') {
                return back()->with('error', 'Hanya CIO Marketing yang dapat mengubah status menjadi Diterima Pelanggan.');
            }
            if ($currentStatus !== 'completed') {
                return back()->with('error', 'Pesanan harus berstatus Selesai Produksi terlebih dahulu.');
            }
            
            $pesanan->update(['status' => 'diterima']);
            return redirect()->route('pesanan.show', $pesanan)->with('success', 'Status pesanan berhasil diubah menjadi Diterima Pelanggan.');
        }

        if ($currentStatus === 'completed') {
            return back()->with('error', 'Status pesanan sudah Selesai Produksi dan tidak dapat diubah lagi oleh CIO Production.');
        }

        // CEK STATUS BERTAHAP (TIDAK BOLEH LONCAT DARI ANTRIAN LANGSUNG KE SELESAI)
        if ($currentStatus === 'queue' && !in_array($newStatus, ['processing', 'delayed'])) {
            return back()->with('error', 'Pesanan harus diubah ke status "Diproses" terlebih dahulu, tidak bisa langsung ke "Selesai".')->withInput();
        }

        // === ATURAN VALIDASI DINAMIS UNTUK TANGGAL ===
        $rulesTanggalDiproses = ['nullable', 'date'];
        // Wajib isi Tanggal Mulai Produksi HANYA jika status lama Antrian dan mau ubah ke Diproses/Tertunda
        if ($currentStatus === 'queue' && in_array($newStatus, ['processing', 'delayed'])) {
            $rulesTanggalDiproses = ['required', 'date'];
        }

        $rulesTanggalSelesai = ['nullable', 'date'];
        // Wajib isi Tanggal Selesai Produksi JIKA status baru adalah Selesai (completed)
        if ($newStatus === 'completed') {
            $rulesTanggalSelesai = ['required', 'date'];
        }

        $data = $request->validate([
            'teknisi_id' => ['nullable', 'exists:teknisi,id'], 
            'status' => ['required', 'in:queue,processing,completed,delayed'],
            'tanggal_diproses' => $rulesTanggalDiproses, 
            'tanggal_selesai' => $rulesTanggalSelesai, 
            'qc_desain' => ['nullable', 'boolean'], 
            'qc_konstruksi' => ['nullable', 'boolean'], 
            'qc_kelistrikan' => ['nullable', 'boolean'], 
            'qc_ketahanan' => ['nullable', 'boolean'], 
            'bahan' => ['nullable', 'array'],
            'bahan.*.bahan_baku_id' => ['nullable', 'exists:bahan_baku,id'],
            'bahan.*.jumlah_pakai' => ['nullable', 'integer', 'min:1'], 
        ], [
            'bahan.*.jumlah_pakai.integer' => 'Jumlah pemakaian harus berupa angka bulat.',
            'bahan.*.jumlah_pakai.min' => 'Jumlah pemakaian minimal 1.',
            'tanggal_diproses.required' => 'Tanggal Mulai Produksi wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal Selesai Produksi wajib diisi.',
        ]);

        // KUNCI TEKNISI & TANGGAL PRODUKSI: Jika status pesanan LAMA sudah "Diproses" (atau selanjutnya), 
        // maka data teknisi & tanggal diproses TIDAK BISA DIRUBAH, wajib pakai data dari database.
        if (in_array($currentStatus, ['processing', 'delayed', 'completed'])) {
            $data['teknisi_id'] = $pesanan->teknisi_id;
            $data['tanggal_diproses'] = $pesanan->tanggal_diproses ? $pesanan->tanggal_diproses->format('Y-m-d') : null;
        } else {
            // Jika status pesanan LAMA masih "Antrian" (queue), boleh ambil dari inputan form
            if (empty($data['teknisi_id'])) {
                $data['teknisi_id'] = $pesanan->teknisi_id;
            }
            // Validasi tambahan: Jika teknisi masih kosong dan status baru diproses/delayed/selesai, tolak
            if (in_array($newStatus, ['processing', 'delayed', 'completed']) && empty($data['teknisi_id'])) {
                 return back()->with('error', 'Harap pilih teknisi terlebih dahulu.')->withInput();
            }

            // Jika tanggal_diproses tidak dikirim, gunakan yang lama dari database
            if (empty($data['tanggal_diproses'])) {
                $data['tanggal_diproses'] = $pesanan->tanggal_diproses ? $pesanan->tanggal_diproses->format('Y-m-d') : null;
            }
        }

        // Konversi 4 checkbox QC ke boolean (true/false)
        $data['qc_desain'] = $request->boolean('qc_desain');
        $data['qc_konstruksi'] = $request->boolean('qc_konstruksi');
        $data['qc_kelistrikan'] = $request->boolean('qc_kelistrikan');
        $data['qc_ketahanan'] = $request->boolean('qc_ketahanan');

        // CEK QC: Jika ingin ubah ke "Selesai Produksi", ke-4 QC wajib dicentang semua
        if ($newStatus === 'completed' && (!$data['qc_desain'] || !$data['qc_konstruksi'] || !$data['qc_kelistrikan'] || !$data['qc_ketahanan'])) {
            // UBAH: Kirim error ke bagian 'qc_error' agar bisa ditangkap di bawah checkbox
            return back()->withErrors(['qc_error' => 'harap lakukan pengecekkan QC'])->withInput();
        }

        $barisBahan = collect($data['bahan'] ?? [])
            ->filter(fn ($baris) => ! empty($baris['bahan_baku_id']) && ! empty($baris['jumlah_pakai']));

        // CEK BAHAN BAKU: Jika ingin ubah ke "Selesai Produksi", wajib ada bahan baku
        // Cek jika form kosong, apakah di database juga kosong?
        if ($newStatus === 'completed' && $barisBahan->isEmpty() && $pesanan->pemakaianBahan()->exists() === false) {
            return back()->with('error', 'Harap isi minimal 1 bahan baku yang dipakai sebelum mengubah status menjadi Selesai Produksi.')->withInput();
        }

        // CEK APAKAH STOK SUDAH PERNAH DIPOTONG SEBELUMNYA (MENCEGAH PENGURANGAN STOK DOBEL)
        $isFirstTimeDeducting = $pesanan->pemakaianBahan()->doesntExist();

        if ($isFirstTimeDeducting) {
            // 1. CEK STOK SEBELUM MENYIMPAN (HANYA SAAT PERTAMA KALI UBAH KE DIPROSES)
            $stokKurang = false;
            $namaBahanKurang = '';
            
            foreach ($barisBahan as $baris) {
                $bahanBaku = BahanBaku::find($baris['bahan_baku_id']);
                if ($bahanBaku && $bahanBaku->stok < $baris['jumlah_pakai']) {
                    $stokKurang = true;
                    $namaBahanKurang = $bahanBaku->nama;
                    break; 
                }
            }

            // 2. JIKA STOK KURANG, UBAH STATUS JADI TERTUNDA & KASIH PESAN ERROR
            if ($stokKurang) {
                $pesanan->update([
                    'teknisi_id' => $data['teknisi_id'],
                    'tanggal_diproses' => $data['tanggal_diproses'] ?? null,
                    'tanggal_selesai' => $data['tanggal_selesai'] ?? null,
                    'qc_desain' => $data['qc_desain'], 
                    'qc_konstruksi' => $data['qc_konstruksi'], 
                    'qc_kelistrikan' => $data['qc_kelistrikan'], 
                    'qc_ketahanan' => $data['qc_ketahanan'], 
                    'status' => 'delayed',
                ]);

                return back()->with('error', "Bahan baku kurang! Stok {$namaBahanKurang} tidak mencukupi.")->withInput();
            }
        }

        // 3. JIKA STOK AMAN (ATAU SUDAH PERNAH DIPOTONG), PROSES UPDATE STATUS
        DB::transaction(function () use ($data, $pesanan, $barisBahan, $isFirstTimeDeducting) {
            $pesanan->update([
                'teknisi_id' => $data['teknisi_id'],
                'tanggal_diproses' => $data['tanggal_diproses'] ?? null,
                'tanggal_selesai' => $data['tanggal_selesai'] ?? null,
                'qc_desain' => $data['qc_desain'], 
                'qc_konstruksi' => $data['qc_konstruksi'], 
                'qc_kelistrikan' => $data['qc_kelistrikan'], 
                'qc_ketahanan' => $data['qc_ketahanan'], 
                'status' => $data['status'],
            ]);

            // KURANGI STOK HANYA JIKA INI PERTAMA KALINYA DIPROSES
            if ($isFirstTimeDeducting) {
                foreach ($barisBahan as $baris) {
                    $bahanBaku = BahanBaku::lockForUpdate()->find($baris['bahan_baku_id']);

                    if (! $bahanBaku) {
                        continue;
                    }

                    $pesanan->pemakaianBahan()->create([
                        'bahan_baku_id' => $bahanBaku->id,
                        'jumlah_pakai' => $baris['jumlah_pakai'],
                    ]);

                    $bahanBaku->decrement('stok', $baris['jumlah_pakai']);
                }
            }
        });

        return redirect()->route('pesanan.show', $pesanan)->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy(Pesanan $pesanan): RedirectResponse
    {
        if ($pesanan->file_desain) {
            $filePath = public_path($pesanan->file_desain);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        if ($pesanan->bukti_pembayaran) {
            $filePathKwitansi = public_path($pesanan->bukti_pembayaran);
            if (File::exists($filePathKwitansi)) {
                File::delete($filePathKwitansi);
            }
        }
        $pesanan->pemakaianBahan()->delete();
        $pesanan->permintaanBahan()->delete();
        $pesanan->delete();

        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil dihapus permanen.');
    }

    private function generateNomorInvoice(): string
    {
        $prefix = 'INV'.now()->format('dmy');
        $urutanTerakhir = Pesanan::where('nomor_invoice', 'like', "{$prefix}%")->count();
        $urutanBaru = str_pad((string) ($urutanTerakhir + 1), 3, '0', STR_PAD_LEFT);
        return "{$prefix}{$urutanBaru}";
    }

    public function downloadPdf(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        $status = $request->input('status');
        $search = $request->input('search');

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $pesanan = Pesanan::query()
            ->with('produk')
            ->when($search, function ($query, $search) {
                $query->where('nama_customer', 'like', "%{$search}%")
                    ->orWhere('nomor_invoice', 'like', "%{$search}%");
            })
            ->when($bulan, function ($query, $bulan) {
                $query->whereMonth('created_at', $bulan);
            })
            ->when($tahun, function ($query, $tahun) {
                $query->whereYear('created_at', $tahun);
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->oldest()
            ->get();

        $pdf = Pdf::loadView('pesanan.pdf', compact('pesanan', 'bulan', 'tahun', 'namaBulan', 'status'));
        
        $namaFile = 'laporan-pesanan-' . ($bulan ? $namaBulan[(int)$bulan] : 'semua') . '-' . ($tahun ?: 'tahun') . '.pdf';
        
        return $pdf->download($namaFile);
    }

    public function downloadStruk(Pesanan $pesanan)
    {
        $pesanan->load(['produk', 'teknisi']);

        $pdf = Pdf::loadView('pesanan.struk', compact('pesanan'))
            ->setPaper([0, 0, 204, 550]); 

        return $pdf->stream('struk-' . $pesanan->nomor_invoice . '.pdf');
    }
}