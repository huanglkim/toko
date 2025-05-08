<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pjhd;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanpiutangExport;

class LaporanpiutangController extends Controller
{
    public function index(Request $request)
    {
        $pelanggans = Pelanggan::where('status', 1)->get();
        return view('penjualan.laporanpiutang', compact('pelanggans'));
    }

    public function filterlaporanpiutang(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggal_awal = Carbon::parse($request->tanggal_awal)->startOfDay();
        $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

        // Ambil supplier yang memiliki piutang > 0 dan belum lunas
        $pelanggans = pelanggan::whereIn(
            'id',
            Pjhd::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                ->where('piutang', '>', 0) // Hanya ambil yang masih memiliki piutang
                ->where('status_piutang', 1) // Ganti dengan status_piutang
                ->pluck('pelanggan_id')
                ->unique(),
        )->get();

        // Jika tidak ada supplier yang memiliki piutang, tampilkan kosong
        if ($pelanggans->isEmpty()) {
            return response()->json(['data' => []]);
        }

        // Buat daftar tanggal dari tanggal awal hingga akhir
        $waktu = [];
        $startDateClone = clone $tanggal_awal;
        while ($startDateClone->lte($tanggal_akhir)) {
            $waktu[] = $startDateClone->format('Y-m-d');
            $startDateClone->addDay();
        }

        $pelangganData = [];

        foreach ($pelanggans as $pelanggan) {
            $pelangganname = $pelanggan->nama;
            $datapj = array_fill(0, count($waktu), 0);

            foreach ($waktu as $index => $periode) {
                $tanggal = Carbon::parse($periode);

                // Ambil total piutang yang masih ada dan belum lunas
                $pj = Pjhd::where('pelanggan_id', $pelanggan->id)
                    ->whereDate('tanggal', $tanggal)
                    ->where('piutang', '>', 0) // Pastikan hanya piutang yang masih ada
                    ->where('status_piutang', 1) // Pastikan belum lunas
                    ->sum('piutang');

                $datapj[$index] = $pj ?: 0;
            }

            // Jika supplier ini tidak memiliki piutang pada semua tanggal, tidak perlu ditampilkan
            if (array_sum($datapj) > 0) {
                $pelangganData[] = [
                    'label' => $pelangganname,
                    'data' => $datapj,
                ];
            }
        }

        // Buat data JSON yang akan dikirim ke frontend
        $data = [];
        foreach ($waktu as $index => $periode) {
            $data[$periode] = [];

            foreach ($pelangganData as $pelanggan) {
                $data[$periode][$pelanggan['label']] = $pelanggan['data'][$index] ?? 0;
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
        $pelanggan_id = $request->pelanggan_id;
        $tanggal_awal = Carbon::parse($request->tanggal_awal)->startOfDay();
        $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

        // Query hanya ambil piutang yang belum lunas
        $query = Pjhd::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->where('piutang', '>', 0)
            ->where('status_piutang', 1); // Ganti dengan status_piutang

        // Jika memilih pelanggan, filter berdasarkan ID
        if (!empty($pelanggan_id)) {
            $query->where('pelanggan_id', $pelanggan_id);
        }

        $laporans = $query->get();
        $jenis = 'Laporan Piutang';

        if ($tipe == 'cetak') {
            return view('penjualan.printpiutang', compact('laporans', 'jenis', 'tanggal_awal', 'tanggal_akhir'));
        }

        if ($tipe == 'excel') {
            return Excel::download(new LaporanpiutangExport($tanggal_awal, $tanggal_akhir, $pelanggan_id), 'laporan_piutang_detail.xlsx');
        }
    }
}
