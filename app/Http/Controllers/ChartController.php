<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function getLabaRugi()
    {
        // Ambil total penjualan
        $totalPenjualan = DB::table('sales')->whereNull('deleted_at')->sum('total_price');

        // Ambil total pembelian
        $totalPembelian = DB::table('purchases')->whereNull('deleted_at')->sum('total_price');

        // Hitung Laba Kotor (Pendapatan - HPP)
        $labaKotor = $totalPenjualan - $totalPembelian;

        // Ambil Modal Awal
        $modalAwal = DB::table('modals')->whereNull('deleted_at')->sum('initial_amount');

        // Hitung Modal Akhir (Modal Awal + Laba Kotor)
        $modalAkhir = $modalAwal + $labaKotor;

        // Kirim data dalam format JSON untuk Chart.js
        return response()->json([
            'total_penjualan' => $totalPenjualan,
            'total_pembelian' => $totalPembelian,
            'laba_kotor' => $labaKotor,
            'modal_awal' => $modalAwal,
            'modal_akhir' => $modalAkhir
        ]);
    }
}
