<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pbhd;
use App\Models\Suplier;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPembelianExport;
use App\Exports\LaporanPembelianDetailExport;

class LaporanpembelianController extends Controller
{
    public function index(Request $request)
    {
        $supliers = Suplier::where('status', 1)->get();
        return view('pembelian.laporanpembelian', compact(['supliers']));
    }

    public function filterlaporanpembelian(Request $request)
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
        $supliers = Suplier::whereIn(
            'id',
            Pbhd::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                ->pluck('suplier_id')
                ->unique(),
        )->get();

        // Buat daftar tanggal dari tanggal awal hingga akhir
        $waktu = [];
        $startDateClone = clone $tanggal_awal;
        while ($startDateClone->lte($tanggal_akhir)) {
            $waktu[] = $startDateClone->format('Y-m-d');
            $startDateClone->addDay(); // Perbaikan dari addDate() ke addDay()
        }

        $suplierData = [];

        foreach ($supliers as $suplier) {
            $supliername = $suplier->nama;
            $datapj = array_fill(0, count($waktu), 0);

            foreach ($waktu as $index => $periode) {
                $tanggal = Carbon::parse($periode);

                // Query jumlah pembelian dari supplier ini di tanggal tertentu
                $pj = Pbhd::where('suplier_id', $suplier->id)->whereDate('tanggal', $tanggal)->sum('total');

                $datapj[$index] = $pj ?: 0;
            }

            $suplierData[] = [
                'label' => $supliername,
                'data' => $datapj,
            ];
        }

        $data = [];
        foreach ($waktu as $index => $periode) {
            $data[$periode] = [];

            foreach ($suplierData as $suplier) {
                $data[$periode][$suplier['label']] = $suplier['data'][$index] ?? 0;
            }

            // Jika tidak ada data sama sekali, tambahkan "Tidak Ada Data"
            if (empty($suplierData)) {
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
        $suplier_id = $request->suplier_id;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
    
        if ($tipe == 'cetak') {
            if ($jenis == 'rekap') {
                $laporans = Pbhd::whereBetween('tanggal', [
                        Carbon::parse($tanggal_awal)->startOfDay(),
                        Carbon::parse($tanggal_akhir)->endOfDay()
                    ])
                    ->when($suplier_id, function ($query) use ($suplier_id) {
                        return $query->where('suplier_id', $suplier_id);
                    })
                    ->get();
        
                return view('pembelian.print_laporan', compact('laporans', 'jenis', 'tanggal_awal', 'tanggal_akhir'));
            }
    
            if ($jenis == 'detail') {
                $laporans = Pbhd::whereBetween('tanggal', [
                        Carbon::parse($tanggal_awal)->startOfDay(),
                        Carbon::parse($tanggal_akhir)->endOfDay()
                    ])
                    ->when($suplier_id, function ($query) use ($suplier_id) {
                        return $query->where('suplier_id', $suplier_id);
                    })
                    ->with('pbdt')
                    ->get();
    
                return view('pembelian.printdetail', compact('laporans', 'jenis', 'tanggal_awal', 'tanggal_akhir'));
            }
        }
    
        if ($tipe == 'excel') {
            if ($jenis == 'rekap') {
                return Excel::download(new LaporanPembelianExport($tanggal_awal, $tanggal_akhir), 'laporan_pembelian.xlsx');
            }
            if ($jenis == 'detail') {
                return Excel::download(new LaporanPembelianDetailExport($tanggal_awal, $tanggal_akhir, $suplier_id), 'laporan_pembelian_detail.xlsx');
            }
        }
    }    
}
