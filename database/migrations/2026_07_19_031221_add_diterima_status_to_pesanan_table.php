<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menambahkan nilai 'diterima' ke dalam ENUM kolom status
        DB::statement("ALTER TABLE pesanan MODIFY COLUMN status ENUM('queue', 'processing', 'completed', 'delayed', 'diterima') NOT NULL DEFAULT 'queue'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan seperti semula (tanpa 'diterima')
        DB::statement("ALTER TABLE pesanan MODIFY COLUMN status ENUM('queue', 'processing', 'completed', 'delayed') NOT NULL DEFAULT 'queue'");
    }
};