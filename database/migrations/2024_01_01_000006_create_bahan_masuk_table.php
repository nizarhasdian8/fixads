<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi')->unique();
            $table->date('tanggal');
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku');
            $table->decimal('jumlah', 12, 2);
            $table->foreignId('permintaan_bahan_id')->nullable()->constrained('permintaan_bahan');
            $table->foreignId('dicatat_oleh')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_masuk');
    }
};
