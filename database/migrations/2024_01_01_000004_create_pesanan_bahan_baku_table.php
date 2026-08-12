<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Mencatat pemakaian bahan baku aktual per pesanan.
        // Diisi oleh CIO Production saat mengubah status pesanan.
        // Setelah tersimpan, stok bahan_baku otomatis berkurang (lihat PesananController).
        Schema::create('pesanan_bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku');
            $table->decimal('jumlah_pakai', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_bahan_baku');
    }
};
