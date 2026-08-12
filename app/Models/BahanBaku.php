<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'satuan',
        'stok',
        'stok_minimum',
    ];

    protected function casts(): array
    {
        return [
            'stok' => 'decimal:2',
            'stok_minimum' => 'decimal:2',
        ];
    }

    public function pemakaian(): HasMany
    {
        return $this->hasMany(PesananBahanBaku::class);
    }

    public function permintaan(): HasMany
    {
        return $this->hasMany(PermintaanBahan::class);
    }

    public function bahanMasuk(): HasMany
    {
        return $this->hasMany(BahanMasuk::class);
    }

    public function isHampirHabis(): bool
    {
        return $this->stok <= $this->stok_minimum;
    }

    public function statusStok(): string
    {
        return $this->isHampirHabis() ? 'Hampir Habis' : 'Aman';
    }
}
