<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_invoice')->unique();
            $table->string('nama_customer');
            $table->string('nomor_hp');
            $table->string('sumber_pesanan');
            $table->foreignId('produk_id')->constrained('produk');
            $table->string('ukuran');
            $table->unsignedInteger('jumlah');
            $table->decimal('harga', 14, 2);
            $table->text('spesifikasi')->nullable();
            $table->string('file_desain')->nullable();
            $table->date('deadline');
            $table->text('catatan')->nullable();
            $table->enum('status', ['queue', 'processing', 'completed', 'delayed'])->default('queue');
            $table->string('kode_teknisi')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
