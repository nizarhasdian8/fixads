<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BahanMasuk extends Model
{
    protected $table = 'bahan_masuk';

    protected $fillable = [
        'nomor_transaksi',
        'tanggal',
        'bahan_baku_id',
        'jumlah',
        'permintaan_bahan_id',
        'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah' => 'decimal:2',
        ];
    }

    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class);
    }

    public function permintaanBahan(): BelongsTo
    {
        return $this->belongsTo(PermintaanBahan::class);
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
