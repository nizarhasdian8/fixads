<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    protected $table = 'teknisi';

    protected $fillable = [
        'nama',
    ];

    // Relasi: Satu teknisi bisa mengerjakan banyak pesanan
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'teknisi_id');
    }
}