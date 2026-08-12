<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananBahanBaku extends Model
{
    protected $table = 'pesanan_bahan_baku';

    protected $fillable = [
        'pesanan_id',
        'bahan_baku_id',
        'jumlah_pakai',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_pakai' => 'decimal:2',
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
}
