<?php

namespace App\Http\Controllers;
use App\Models\Pjhd;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanchartController extends Controller
{
    public function index()
    {
        return view('penjualan.laporancharttahunan');
    }

    public function filterPendapatan(Request $request)
    {
        $bulan_awal = Carbon::createFromFormat('Y-m', $request['bulan_awal']);
        $bulan_akhir = Carbon::createFromFormat('Y-m', $request['bulan_akhir']);
    
        // Buat daftar bulan dalam rentang waktu tersebut
        $bulan = [];
        $startDateClone = clone $bulan_awal;
        while ($startDateClone->lte($bulan_akhir)) {
            $bulan[] = $startDateClone->format('F Y');
            $startDateClone->addMonth();
        }
    
        // Inisialisasi array untuk menyimpan total pendapatan per bulan
        $sales = [];
    
        foreach ($bulan as $periode) {
            $tanggal = Carbon::createFromFormat('F Y', $periode);
    
            // Query untuk mengambil total pendapatan per bulan
            $totalPendapatan = Pjhd::whereMonth('tanggal', $tanggal->month)
                ->whereYear('tanggal', $tanggal->year)
                ->sum('total');
    
            $sales[$periode] = $totalPendapatan + 0 ?: 0; // Jika tidak ada pendapatan, set ke 0
        }
    
        return response()->json(['data' => $sales]);
    }
}
