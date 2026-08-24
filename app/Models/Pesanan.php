<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'kode_teknisi',
        'teknisi_id', 
        'created_by',
        'status_pembayaran', 
        'bukti_pembayaran',
        'nominal_pembayaran', // TAMBAHAN UNTUK REVISI 5
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'harga' => 'decimal:2',
            'nominal_pembayaran' => 'decimal:2', // TAMBAHAN UNTUK REVISI 5
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

    // TAMBAHAN UNTUK REVISI 4: Relasi ke Teknisi
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