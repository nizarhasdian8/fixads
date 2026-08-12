<?php

namespace Database\Seeders;

use App\Models\BahanBaku;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Marketing Fix Advertising',
            'email' => 'marketing@fixadvertising.com',
            'password' => Hash::make('password123'),
            'role' => 'cio_marketing',
        ]);

        User::create([
            'name' => 'Production Fix Advertising',
            'email' => 'production@fixadvertising.com',
            'password' => Hash::make('password123'),
            'role' => 'cio_production',
        ]);

        $produk = [
            ['kode_produk' => 'NBX', 'nama_produk' => 'Neon Box', 'deskripsi' => 'Signage box dengan pencahayaan LED di dalamnya.'],
            ['kode_produk' => 'NFX', 'nama_produk' => 'Neon Flex', 'deskripsi' => 'Lampu LED fleksibel menyerupai neon untuk signage outdoor/indoor.'],
            ['kode_produk' => 'ABL', 'nama_produk' => 'Acrylic Backlight', 'deskripsi' => 'Papan akrilik dengan pencahayaan dari belakang.'],
            ['kode_produk' => 'SLB', 'nama_produk' => 'Slimbox', 'deskripsi' => 'Signage tipis dengan rangka aluminium.'],
            ['kode_produk' => 'RTX', 'nama_produk' => 'Running Text', 'deskripsi' => 'Display LED berjalan untuk informasi atau promosi.'],
        ];
        foreach ($produk as $p) {
            Produk::create($p + ['status_aktif' => true]);
        }

        $bahanBaku = [
            ['kode' => 'BB-001', 'nama' => 'Acrylic 3mm', 'kategori' => 'Bahan Utama', 'satuan' => 'lembar', 'stok' => 50, 'stok_minimum' => 10],
            ['kode' => 'BB-002', 'nama' => 'LED Module', 'kategori' => 'Elektronik', 'satuan' => 'pcs', 'stok' => 200, 'stok_minimum' => 50],
            ['kode' => 'BB-003', 'nama' => 'Power Supply', 'kategori' => 'Elektronik', 'satuan' => 'pcs', 'stok' => 30, 'stok_minimum' => 10],
            ['kode' => 'BB-004', 'nama' => 'Rangka Aluminium', 'kategori' => 'Bahan Utama', 'satuan' => 'meter', 'stok' => 80, 'stok_minimum' => 20],
            ['kode' => 'BB-005', 'nama' => 'Neon Flex LED', 'kategori' => 'Elektronik', 'satuan' => 'meter', 'stok' => 100, 'stok_minimum' => 25],
            ['kode' => 'BB-006', 'nama' => 'Kabel Listrik', 'kategori' => 'Elektronik', 'satuan' => 'meter', 'stok' => 8, 'stok_minimum' => 15],
            ['kode' => 'BB-007', 'nama' => 'Stiker Vinyl', 'kategori' => 'Bahan Pendukung', 'satuan' => 'meter', 'stok' => 40, 'stok_minimum' => 10],
        ];
        foreach ($bahanBaku as $b) {
            BahanBaku::create($b);
        }
    }
}
