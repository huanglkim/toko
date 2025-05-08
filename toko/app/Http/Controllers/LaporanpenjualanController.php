<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pjhd;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPenjualanExport;
use App\Exports\LaporanPenjualanDetailExport;

class LaporanpenjualanController extends Controller
{
    public function index(Request $request)
    {
        $pelanggans = Pelanggan::where('status', 1)->get();
        return view('penjualan.laporanpenjualan', compact(['pelanggans']));
    }

    public function filterlaporanpenjualan(Request $request)
    {
        // Validasi input tanggal agar tidak error
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        // Konversi tanggal dari request menggunakan Carbon
        $tanggal_awal = Carbon::parse($request->tanggal_awal)->startOfDay();
        $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

        // Ambil semua supplier yang memiliki transaksi dalam rentang waktu
        $pelanggans = Pelanggan::whereIn(
            'id',
            Pjhd::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                ->pluck('pelanggan_id')
                ->unique(),
        )->get();

        // Buat daftar tanggal dari tanggal awal hingga akhir
        $waktu = [];
        $startDateClone = clone $tanggal_awal;
        while ($startDateClone->lte($tanggal_akhir)) {
            $waktu[] = $startDateClone->format('Y-m-d');
            $startDateClone->addDay(); // Perbaikan dari addDate() ke addDay()
        }

        $pelangganData = [];

        foreach ($pelanggans as $pelanggan) {
            $pelangganname = $pelanggan->nama;
            $datapj = array_fill(0, count($waktu), 0);

            foreach ($waktu as $index => $periode) {
                $tanggal = Carbon::parse($periode);

                $pj = Pjhd::where('pelanggan_id', $pelanggan->id)->whereDate('tanggal', $tanggal)->sum('total');

                $datapj[$index] = $pj ?: 0;
            }

            $pelangganData[] = [
                'label' => $pelangganname,
                'data' => $datapj,
            ];
        }

        $data = [];
        foreach ($waktu as $index => $periode) {
            $data[$periode] = [];

            foreach ($pelangganData as $pelanggan) {
                $data[$periode][$pelanggan['label']] = $pelanggan['data'][$index] ?? 0;
            }

            // Jika tidak ada data sama sekali, tambahkan "Tidak Ada Data"
            if (empty($pelangganData)) {
                $data[$periode]['Tidak Ada Data'] = 0;
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
        $jenis = $request->jenis;
        $pelanggan_id = $request->pelanggan_id;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
    
        if ($tipe == 'cetak') {
            if ($jenis == 'rekap') {
                $laporans = Pjhd::whereBetween('tanggal', [
                        Carbon::parse($tanggal_awal)->startOfDay(),
                        Carbon::parse($tanggal_akhir)->endOfDay()
                    ])
                    ->when($pelanggan_id, function ($query) use ($pelanggan_id) {
                        return $query->where('pelanggan_id', $pelanggan_id);
                    })
                    ->get();
        
                return view('penjualan.print_laporan', compact('laporans', 'jenis', 'tanggal_awal', 'tanggal_akhir'));
            }
    
            if ($jenis == 'detail') {
                $laporans = Pjhd::whereBetween('tanggal', [
                        Carbon::parse($tanggal_awal)->startOfDay(),
                        Carbon::parse($tanggal_akhir)->endOfDay()
                    ])
                    ->when($pelanggan_id, function ($query) use ($pelanggan_id) {
                        return $query->where('pelanggan_id', $pelanggan_id);
                    })
                    ->with('pjdt')
                    ->get();
    
                return view('penjualan.printdetail', compact('laporans', 'jenis', 'tanggal_awal', 'tanggal_akhir'));
            }
        }
    
        if ($tipe == 'excel') {
            if ($jenis == 'rekap') {
                return Excel::download(new LaporanPenjualanExport($tanggal_awal, $tanggal_akhir), 'laporan_penjualan.xlsx');
            }
            if ($jenis == 'detail') {
                return Excel::download(new LaporanPenjualanDetailExport($tanggal_awal, $tanggal_akhir, $pelanggan_id), 'laporan_penjualan_detail.xlsx');
            }
        }
    }    
}
