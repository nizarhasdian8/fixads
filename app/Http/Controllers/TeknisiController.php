<?php

namespace App\Http\Controllers;

use App\Models\Teknisi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeknisiController extends Controller
{
    public function index(): View
    {
        // Ambil semua data teknisi, dan hitung jumlah pesanan yang dikerjakan bulan ini
        $teknisis = Teknisi::withCount(['pesanans' => function ($query) {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }])->get();

        return view('teknisi.index', [
            'teknisis' => $teknisis,
        ]);
    }
}