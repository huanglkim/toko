<?php

namespace App\Http\Controllers;

use App\Models\Pjhd;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanchartharianController extends Controller
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

        // Buat daftar tanggal dalam rentang waktu tersebut
        $hari = [];
        $startDateClone = clone $tanggal_awal;
        while ($startDateClone->lte($tanggal_akhir)) {
            $hari[] = $startDateClone->format('Y-m-d'); // Format tanggal: Y-m-d
            $startDateClone->addDay(); // Menambahkan satu hari
        }

        // Inisialisasi array untuk menyimpan total pendapatan per hari
        $sales = [];

        foreach ($hari as $tanggal) {
            // Query untuk mengambil total pendapatan per hari
            $totalPendapatan = Pjhd::whereDate('tanggal', $tanggal)->sum('total');

            // Menyimpan total pendapatan per hari, jika tidak ada pendapatan maka set 0
            $sales[$tanggal] = $totalPendapatan + 0 ?: 0;
        }

        // Menampilkan view dengan data yang sudah diproses
        return view('penjualan.laporanchartbulanan', compact('sales', 'tanggal_awal', 'tanggal_akhir'));
    }
}
