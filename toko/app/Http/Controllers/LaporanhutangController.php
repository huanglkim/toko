<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pbhd;
use App\Models\Suplier;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanhutangExport;

class LaporanhutangController extends Controller
{
    public function index(Request $request)
    {
        $supliers = Suplier::where('status', 1)->get();
        return view('pembelian.laporanhutang', compact('supliers'));
    }

    public function filterlaporanhutang(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggal_awal = Carbon::parse($request->tanggal_awal)->startOfDay();
        $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

        // Ambil supplier yang memiliki hutang > 0 dan belum lunas
        $supliers = Suplier::whereIn(
            'id',
            Pbhd::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                ->where('hutang', '>', 0) // Hanya ambil yang masih memiliki hutang
                ->where('status_hutang', 1) // Ganti dengan status_hutang
                ->pluck('suplier_id')
                ->unique(),
        )->get();

        // Jika tidak ada supplier yang memiliki hutang, tampilkan kosong
        if ($supliers->isEmpty()) {
            return response()->json(['data' => []]);
        }

        // Buat daftar tanggal dari tanggal awal hingga akhir
        $waktu = [];
        $startDateClone = clone $tanggal_awal;
        while ($startDateClone->lte($tanggal_akhir)) {
            $waktu[] = $startDateClone->format('Y-m-d');
            $startDateClone->addDay();
        }

        $suplierData = [];

        foreach ($supliers as $suplier) {
            $supliername = $suplier->nama;
            $datapj = array_fill(0, count($waktu), 0);

            foreach ($waktu as $index => $periode) {
                $tanggal = Carbon::parse($periode);

                // Ambil total hutang yang masih ada dan belum lunas
                $pj = Pbhd::where('suplier_id', $suplier->id)
                    ->whereDate('tanggal', $tanggal)
                    ->where('hutang', '>', 0) // Pastikan hanya hutang yang masih ada
                    ->where('status_hutang', 1) // Pastikan belum lunas
                    ->sum('hutang');

                $datapj[$index] = $pj ?: 0;
            }

            // Jika supplier ini tidak memiliki hutang pada semua tanggal, tidak perlu ditampilkan
            if (array_sum($datapj) > 0) {
                $suplierData[] = [
                    'label' => $supliername,
                    'data' => $datapj,
                ];
            }
        }

        // Buat data JSON yang akan dikirim ke frontend
        $data = [];
        foreach ($waktu as $index => $periode) {
            $data[$periode] = [];

            foreach ($suplierData as $suplier) {
                $data[$periode][$suplier['label']] = $suplier['data'][$index] ?? 0;
            }
        }

        return response()->json(['data' => $data]);
    }

    public function print(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date',
        ]);

        $tipe = $request->tipe;
        $suplier_id = $request->suplier_id;
        $tanggal_awal = Carbon::parse($request->tanggal_awal)->startOfDay();
        $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

        // Query hanya ambil hutang yang belum lunas
        $query = Pbhd::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->where('hutang', '>', 0)
            ->where('status_hutang', 1); // Ganti dengan status_hutang

        // Jika memilih suplier, filter berdasarkan ID
        if (!empty($suplier_id)) {
            $query->where('suplier_id', $suplier_id);
        }

        $laporans = $query->get();
        $jenis = 'Laporan Hutang';

        if ($tipe == 'cetak') {
            return view('pembelian.printhutang', compact('laporans', 'jenis', 'tanggal_awal', 'tanggal_akhir'));
        }

        if ($tipe == 'excel') {
            return Excel::download(new LaporanhutangExport($tanggal_awal, $tanggal_akhir, $suplier_id), 'laporan_hutang_detail.xlsx');
        }
    }
}
