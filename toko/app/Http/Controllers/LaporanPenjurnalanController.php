<?php

// Controller LaporanPenjurnalanController.php

namespace App\Http\Controllers;

use App\Models\Accjurnal;
use App\Models\Accperkiraan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPenjurnalanExport;

class LaporanPenjurnalanController extends Controller
{
    public function index()
    {
        // Ambil semua Accperkiraan berdasarkan nama_acc
        $accperkiraans = Accperkiraan::all();
        return view('jurnalkhusus.laporanpenjurnalan');
    }

    public function filterlaporanpenjurnalan(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggal_awal = Carbon::parse($request->tanggal_awal)->startOfDay();
        $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

        // Ambil Accperkiraan yang relevan berdasarkan kode_acc yang ada di Accjurnal dalam rentang tanggal
        $accperkiraans = Accperkiraan::whereIn(
            'kode_acc',
            Accjurnal::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                ->pluck('kode_acc')
                ->unique(),
        )->get();

        if ($accperkiraans->isEmpty()) {
            return response()->json(['data' => []]);
        }

        $waktu = [];
        $startDateClone = clone $tanggal_awal;
        while ($startDateClone->lte($tanggal_akhir)) {
            $waktu[] = $startDateClone->format('Y-m-d');
            $startDateClone->addDay();
        }

        $accperkiraanData = [];

        foreach ($accperkiraans as $accperkiraan) {
            $accperkiraanname = $accperkiraan->nama; // Ambil nama_acc dari tabel Accperkiraan
            $dataaj = array_fill(0, count($waktu), 0);

            foreach ($waktu as $index => $periode) {
                $tanggal = Carbon::parse($periode);

                // Ambil total hutang yang masih ada dan belum lunas
                $aj = Accjurnal::where('kode_acc', $accperkiraan->kode_acc)->whereDate('tanggal', $tanggal)->sum('jumlah'); // Asumsi jumlah adalah kolom yang ingin dijumlahkan

                $dataaj[$index] = $aj ?: 0;
            }

            // Jika supplier ini tidak memiliki hutang pada semua tanggal, tidak perlu ditampilkan
            if (array_sum($dataaj) > 0) {
                $accperkiraanData[] = [
                    'label' => $accperkiraanname,
                    'data' => $dataaj,
                ];
            }
        }

        // Buat data JSON yang akan dikirim ke frontend
        $data = [];
        foreach ($waktu as $index => $periode) {
            $data[$periode] = [];

            foreach ($accperkiraanData as $accperkiraan) {
                $data[$periode][$accperkiraan['label']] = $accperkiraan['data'][$index] ?? 0;
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
        $nama_acc = $request->nama_acc;
        $tanggal_awal = Carbon::parse($request->tanggal_awal)->startOfDay();
        $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

        $jenis = 'Laporan Penjurnalan';

        // Ambil data Accjurnal sesuai dengan rentang tanggal
        $accjurnals = Accjurnal::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->when($nama_acc, function ($query) use ($nama_acc) {
                return $query->whereHas('accperkiraan', function ($query) use ($nama_acc) {
                    $query->where('nama_acc', 'like', "%$nama_acc%");
                });
            })
            ->get();

        if ($tipe == 'cetak') {
            return view('jurnalkhusus.printpenjurnalan', compact('accjurnals', 'jenis', 'tanggal_awal', 'tanggal_akhir'));
        }

        if ($tipe == 'excel') {
            return Excel::download(new LaporanPenjurnalanExport($tanggal_awal, $tanggal_akhir, $nama_acc), 'laporan_penjurnalan_detail.xlsx');
        }
    }
}
