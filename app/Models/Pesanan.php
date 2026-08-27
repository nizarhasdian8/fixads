<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property Carbon $deadline
 * @property Carbon|null $tanggal_diproses
 * @property Carbon|null $tanggal_selesai
 * @property string $status
 * @property string $nomor_invoice
 * @property string $nama_customer
 */
class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'nomor_invoice',
        'nama_customer',
        'nomor_hp',
        'sumber_pesanan',
        'produk_id',
        'ukuran',
        'jumlah',
        'harga',
        'spesifikasi',
        'file_desain',
        'deadline',
        'catatan',
        'status',
        'tanggal_diproses', 
        'tanggal_selesai', 
        'kode_teknisi',
        'teknisi_id', 
        'qc_desain', 
        'qc_konstruksi', 
        'qc_kelistrikan', 
        'qc_ketahanan', 
        'created_by',
        'status_pembayaran', 
        'bukti_pembayaran',
        'nominal_pembayaran', 
        'bukti_pelunasan', // <-- TAMBAHAN BARU
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'tanggal_diproses' => 'date', 
            'tanggal_selesai' => 'date', 
            'harga' => 'decimal:2',
            'nominal_pembayaran' => 'decimal:2',
            'qc_desain' => 'boolean', 
            'qc_konstruksi' => 'boolean', 
            'qc_kelistrikan' => 'boolean', 
            'qc_ketahanan' => 'boolean', 
        ];
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(Teknisi::class, 'teknisi_id');
    }

    public function pemakaianBahan(): HasMany
    {
        return $this->hasMany(PesananBahanBaku::class);
    }

    public function permintaanBahan(): HasMany
    {
        return $this->hasMany(PermintaanBahan::class);
    }

    public static function statusOptions(): array
    {
        return [
            'queue' => 'Antrian',
            'processing' => 'Diproses',
            'completed' => 'Selesai',
            'delayed' => 'Tertunda',
            'diterima'=> 'Diterima Pelanggan'
        ];
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'queue' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
            'processing' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
            'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            'delayed' => 'bg-red-50 text-red-700 ring-red-600/20',
            'diterima' => 'bg-green-50 text-green-700 ring-green-600/20',
            default => 'bg-slate-100 text-slate-700 ring-slate-600/20',
        };
    }
}