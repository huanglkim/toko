<?php

namespace App\Http\Controllers;

use App\Models\Pjdt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanfavproController extends Controller
{
    public function index(Request $request)
    {
        // Menentukan rentang tanggal default (misalnya bulan ini)
        $tanggal_awal = Carbon::now()->startOfMonth(); // Awal bulan ini
        $tanggal_akhir = Carbon::now(); // Hari ini

        // Jika ada filter tanggal yang dikirim, gunakan tanggal filter tersebut
        if ($request->has('tanggal_awal') && $request->has('tanggal_akhir')) {
            $tanggal_awal = Carbon::createFromFormat('Y-m-d', $request->tanggal_awal);
            $tanggal_akhir = Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir);
        }

        // Ambil 10 barang terlaris berdasarkan jumlah pembelian dalam rentang tanggal yang dipilih
        $topProducts = Pjdt::select('barang_id', DB::raw('SUM(qty) as total_qty'))
            ->join('pjhd', 'pjdt.pjhd_id', '=', 'pjhd.id') // Menggabungkan tabel pjdt dengan pjhd
            ->whereBetween('pjhd.tanggal', [$tanggal_awal, $tanggal_akhir])  // Menggunakan tanggal dari pjhd
            ->groupBy('barang_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();
        // dd($topProducts);
     
        return view('penjualan.laporanfavpro', compact('topProducts', 'tanggal_awal', 'tanggal_akhir'));
    }
}
