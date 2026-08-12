<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PermintaanBahan extends Model
{
    protected $table = 'permintaan_bahan';

    protected $fillable = [
        'nomor_permintaan',
        'pesanan_id',
        'bahan_baku_id',
        'jumlah',
        'status',
        'catatan',
        'diajukan_oleh',
        'diproses_oleh',
        'diproses_at',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'diproses_at' => 'datetime',
        ];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class);
    }

    public function pengaju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function pemroses(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function bahanMasuk(): HasOne
    {
        return $this->hasOne(BahanMasuk::class);
    }

    public static function statusOptions(): array
    {
        return [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            default => 'bg-slate-100 text-slate-700 ring-slate-600/20',
        };
    }
}