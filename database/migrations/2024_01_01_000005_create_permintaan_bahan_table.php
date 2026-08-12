<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_bahan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_permintaan')->unique();
            $table->foreignId('pesanan_id')->nullable()->constrained('pesanan');
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku');
            $table->decimal('jumlah', 12, 2);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();
            $table->foreignId('diajukan_oleh')->constrained('users');
            $table->foreignId('diproses_oleh')->nullable()->constrained('users');
            $table->timestamp('diproses_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_bahan');
    }
};
