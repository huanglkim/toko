<?php

namespace App\Http\Controllers;

use App\Models\Accperkiraan;
use App\Models\Accmutasi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LabaKotorBersihExport;
use App\Exports\LabaKotorBersihTahunExport;
use App\Traits\LabaRugi;

class LabaKotorBersihController extends Controller
{
    use LabaRugi;

    public function index()
    {
        return view('jurnalkhusus.labakotorbersih');
    }
    // public function filterlaba(Request $request)
    // {
    //     $request->validate([
    //         'bulan' => 'required|date_format:m',
    //         'tahun' => 'required|date_format:Y',
    //     ]);

    //     $bulan = (int) $request->bulan;
    //     $tahun = (int) $request->tahun;
    //     $toko_id = 1;

    //     // Ambil daftar kode akun yang memiliki mutasi pada tahun dan bulan yang dipilih
    //     $kode_accs = Accmutasi::where('tahun', $tahun)
    //     ->where('bulan', $bulan) // Hanya mutasi bulan yang dipilih
    //     ->pluck('kode_acc')
    //     ->unique();

    //     if ($kode_accs->isEmpty()) {
    //         return response()->json(['data' => []]);
    //     }

    //     // Ambil akun pendapatan (kelompok 4) dari tabel accperkiraan
    //     $pendapatan = Accperkiraan::whereIn('kode_acc', $kode_accs)
    //     ->where('kelompok', 4) // Kelompok untuk pendapatan
    //     ->get();

    //     foreach ($pendapatan as $akun) {
    //             // Hitung saldo pendapatan
    //          $this->saldoLabarugiBulanan($tahun, $bulan, $akun);
    //     }
    //     // Ambil akun HPP (kelompok 5) dari tabel accperkiraan
    //     $hpp = Accperkiraan::whereIn('kode_acc', $kode_accs)
    //         ->where('kelompok', 5) // Kelompok untuk HPP
    //         ->get();

    //     // Ambil akun biaya (kelompok 6) dari tabel accperkiraan
    //     $biaya = Accperkiraan::whereIn('kode_acc', $kode_accs)
    //         ->where('kelompok', 6) // Kelompok untuk biaya
    //         ->get();

    //     // Hitung saldo HPP
    //     foreach ($hpp as $akun) {
    //         $mutasi = Accmutasi::where('kode_acc', $akun->kode_acc)
    //             ->where('tahun', $tahun)
    //             ->where('bulan', $bulan) // Hanya bulan yang dipilih
    //             ->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')
    //             ->first();

    //         $akun->saldo = $mutasi->total_debet - $mutasi->total_kredit;
    //     }

    //     // Hitung saldo biaya
    //     foreach ($biaya as $akun) {
    //         $mutasi = Accmutasi::where('kode_acc', $akun->kode_acc)
    //             ->where('tahun', $tahun)
    //             ->where('bulan', $bulan) // Hanya bulan yang dipilih
    //             ->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')
    //             ->first();

    //         $akun->saldo = $mutasi->total_debet - $mutasi->total_kredit;
    //     }

    //     return response()->json([
    //         'data' => [
    //             'pendapatan' => $pendapatan,
    //             'hpp' => $hpp,
    //             'biaya' => $biaya,
    //         ],
    //         'totalPendapatan' => $pendapatan->sum('saldo'),
    //         'totalHpp' => $hpp->sum('saldo'),
    //         'totalBiaya' => $biaya->sum('saldo'),
    //     ]);
    // }

    public function printLaba(Request $request)
    {
        $bulan = $request->input('bulan', '01');
        $tahun = $request->input('tahun');
        $toko_id = 1;

        $kodeAccList = Accmutasi::where('tahun', $tahun)
            ->where('bulan', $bulan) // Hanya bulan yang dipilih
            ->where('toko_id', $toko_id)
            ->pluck('kode_acc')
            ->unique();

        $pendapatan = $this->saldoLabarugiBulanan($tahun, $bulan, $kodeAccList, $toko_id, 4);
        $totalPendapatan = $pendapatan->sum('saldo');

        $hpp = $this->saldoLabarugiBulanan($tahun, $bulan, $kodeAccList, $toko_id, 5);
        $totalHpp = $hpp->sum('saldo');

        $biaya = $this->saldoLabarugiBulanan($tahun, $bulan, $kodeAccList, $toko_id, 6);
        $totalBiaya = $biaya->sum('saldo');

        $labaataurugi = $this->labarugiBulanan($tahun, $bulan, $toko_id);

        return view('jurnalkhusus.printlaba', [
            'pendapatan' => $pendapatan,
            'hpp' => $hpp,
            'biaya' => $biaya,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'totalPendapatan' => $totalPendapatan,
            'totalHpp' => $totalHpp,
            'totalBiaya' => $totalBiaya,
            'labaataurugi' => $labaataurugi,
        ]);
    }

    public function exportToExcelLaba(Request $request)
    {
        $bulan = $request->input('bulan', '01');
        $tahun = $request->input('tahun');
        $toko_id = $request->input('toko_id');

        return Excel::download(new LabaKotorBersihExport($bulan, $tahun, $toko_id), 'labakotorbersih.xlsx');
    }

    // public function filterTahunlaba(Request $request)
    // {
    //     $request->validate([
    //         'bulan_awal' => 'required|date_format:m',
    //         'tahun_awal' => 'required|date_format:Y',
    //         'bulan_akhir' => 'required|date_format:m',
    //         'tahun_akhir' => 'required|date_format:Y',
    //     ]);

    //     $bulan_awal = (int) $request->bulan_awal;
    //     $tahun_awal = (int) $request->tahun_awal;
    //     $bulan_akhir = (int) $request->bulan_akhir;
    //     $tahun_akhir = (int) $request->tahun_akhir;
    //     $kode_acc = (int) $request->kode_acc;

    //     $toko_id = 1;

    //     $totalPendapatan = [];
    //     $totalHpp = [];
    //     $totalBiaya = [];
    //     $pendapatanData = [];
    //     $hppData = [];
    //     $biayaData = [];

    //     $currentYear = $tahun_awal;
    //     $currentMonth = $bulan_awal;
    //     $kode_accs = $kode_acc;

    //     while (true) {
    //         // Pendapatan - Kelompok 4
    //         $pendapatan = Accperkiraan::whereIn('kode_acc', $kode_accs)->where('kelompok', 4)->where('tipe', 'D')->get();

    //         // HPP - Kelompok 5
    //         $hpp = Accperkiraan::whereIn('kode_acc', $kode_accs)->where('kelompok', 5)->where('tipe', 'D')->get();

    //         // Biaya - Kelompok 6
    //         $biaya = Accperkiraan::whereIn('kode_acc', $kode_accs)->where('kelompok', 6)->where('tipe', 'D')->get();

    //         // Hitung total Pendapatan
    //         $totalPendapatan[$currentMonth] = 0;
    //         foreach ($pendapatan as $data) {
    //             $mutasi = Accmutasi::where('kode_acc', $data->kode_acc)->where('tahun', $currentYear)->where('bulan', $currentMonth)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

    //             $saldo = $mutasi ? $mutasi->total_kredit - $mutasi->total_debet : 0;

    //             $totalPendapatan[$currentMonth] += $saldo;

    //             $pendapatanData[$data->kode_acc][$currentMonth] = [
    //                 'saldo' => $saldo,
    //                 'nama_acc' => $data->nama_acc,
    //             ];
    //         }

    //         // Hitung total HPP
    //         $totalHpp[$currentMonth] = 0;
    //         foreach ($hpp as $data) {
    //             $mutasi = Accmutasi::where('kode_acc', $data->kode_acc)->where('tahun', $currentYear)->where('bulan', $currentMonth)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

    //             $saldo = $mutasi ? $mutasi->total_debet - $mutasi->total_kredit : 0;
    //             $totalHpp[$currentMonth] += $saldo;

    //             $hppData[$data->kode_acc][$currentMonth] = [
    //                 'saldo' => $saldo,
    //                 'nama_acc' => $data->nama_acc,
    //             ];
    //         }

    //         // Hitung total Biaya
    //         $totalBiaya[$currentMonth] = 0;
    //         foreach ($biaya as $data) {
    //             $mutasi = Accmutasi::where('kode_acc', $data->kode_acc)->where('tahun', $currentYear)->where('bulan', $currentMonth)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

    //             $saldo = $mutasi ? $mutasi->total_debet - $mutasi->total_kredit : 0;
    //             $totalBiaya[$currentMonth] += $saldo;

    //             $biayaData[$data->kode_acc][$currentMonth] = [
    //                 'saldo' => $saldo,
    //                 'nama_acc' => $data->nama_acc,
    //             ];
    //         }

    //         // Cek apakah sudah mencapai akhir rentang waktu yang dipilih
    //         if ($currentYear == $tahun_akhir && $currentMonth == $bulan_akhir) {
    //             break;
    //         }

    //         // Pindah ke bulan berikutnya
    //         $currentMonth++;
    //         if ($currentMonth > 12) {
    //             $currentMonth = 1;
    //             $currentYear++;
    //         }
    //     }

    //     return response()->json([
    //         'data' => [
    //             'pendapatan' => $pendapatanData,
    //             'hpp' => $hppData,
    //             'biaya' => $biayaData,
    //         ],
    //         'totalPendapatan' => $totalPendapatan,
    //         'totalHpp' => $totalHpp,
    //         'totalBiaya' => $totalBiaya,
    //         'bulan_awal' => $bulan_awal,
    //         'bulan_akhir' => $bulan_akhir,
    //         'tahun_awal' => $tahun_awal,
    //         'tahun_akhir' => $tahun_akhir,
    //     ]);
    // }
    public function printTahunLaba(Request $request)
    {
        $bulan_awal = $request->input('bulan_awal');
        $bulan_akhir = $request->input('bulan_akhir');
        $toko_id = 1;

        $tahun_awal = substr($bulan_awal, 0, 4);
        $bulan_awal = substr($bulan_awal, 5, 2);
        $tahun_akhir = substr($bulan_akhir, 0, 4);
        $bulan_akhir = substr($bulan_akhir, 5, 2);

        $pendapatanData = [];
        $hppData = [];
        $biayaData = [];

        $currentYear = $tahun_awal;
        $currentMonth = $bulan_awal;

        while (true) {
            $kodeAccList = Accmutasi::where('tahun', $currentYear)->where('bulan', $currentMonth)->where('toko_id', $toko_id)->pluck('kode_acc')->unique();
 
            $pendapatan = Accperkiraan::whereIn('kode_acc', $kodeAccList)->where('kelompok', 4)->get();
            $hpp = Accperkiraan::whereIn('kode_acc', $kodeAccList)->where('kelompok', 5)->get();
            $biaya = Accperkiraan::whereIn('kode_acc', $kodeAccList)->where('kelompok', 6)->get();

            foreach ($pendapatan as $data) {
                for ($bulan = $bulan_awal; $bulan <= $bulan_akhir; $bulan++) {
                    $bulanString = str_pad($bulan, 2, '0', STR_PAD_LEFT);
                    $mutasi = Accmutasi::where('kode_acc', $data->kode_acc)->where('tahun', $currentYear)->where('bulan', $bulan)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

                    $saldo = $mutasi ? $mutasi->total_kredit - $mutasi->total_debet : 0;

                    $pendapatanData[$data->kode_acc][$bulanString] = [
                        'saldo' => $saldo,
                        'nama_acc' => $data->nama_acc,
                    ];
                }
            }

            foreach ($hpp as $data) {
                for ($bulan = $bulan_awal; $bulan <= $bulan_akhir; $bulan++) {
                    $bulanString = str_pad($bulan, 2, '0', STR_PAD_LEFT);
                    $mutasi = Accmutasi::where('kode_acc', $data->kode_acc)->where('tahun', $currentYear)->where('bulan', $bulan)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

                    $saldo = $mutasi ? $mutasi->total_debet - $mutasi->total_kredit : 0;

                    $hppData[$data->kode_acc][$bulanString] = [
                        'saldo' => $saldo,
                        'nama_acc' => $data->nama_acc,
                    ];
                }
            }

            foreach ($biaya as $data) {
                for ($bulan = $bulan_awal; $bulan <= $bulan_akhir; $bulan++) {
                    $bulanString = str_pad($bulan, 2, '0', STR_PAD_LEFT);
                    $mutasi = Accmutasi::where('kode_acc', $data->kode_acc)->where('tahun', $currentYear)->where('bulan', $bulan)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

                    $saldo = $mutasi ? $mutasi->total_debet - $mutasi->total_kredit : 0;

                    $biayaData[$data->kode_acc][$bulanString] = [
                        'saldo' => $saldo,
                        'nama_acc' => $data->nama_acc,
                    ];
                }
            }

            if ($currentYear == $tahun_akhir && $currentMonth == $bulan_akhir) {
                break;
            }

            $currentMonth++;
            if ($currentMonth > 12) {
                $currentMonth = 1;
                $currentYear++;
            }
        }

        return view('jurnalkhusus.printtahunlaba', [
            'pendapatan' => $pendapatan,
            'hpp' => $hpp,
            'biaya' => $biaya,
            'bulan_awal' => $bulan_awal,
            'bulan_akhir' => $bulan_akhir,
            'tahun_awal' => $tahun_awal,
            'tahun_akhir' => $tahun_akhir,
            'pendapatanData' => $pendapatanData,
            'hppData' => $hppData,
            'biayaData' => $biayaData,
        ]);
    }

    //     public function exportToExcelTahunLaba(Request $request)
    // {
    //     $bulan = $request->input('bulan', '01');
    //     $tahun = $request->input('tahun');
    //     $toko_id = $request->input('toko_id');

    //     // Validasi input
    //     if (!$tahun || !$toko_id) {
    //         return response()->json(['error' => 'Tahun atau toko_id tidak valid.'], 400);
    //     }

    //     try {
    //         return Excel::download(new LabaKotorBersihTahunExport($bulan, $tahun, $toko_id), 'labakotorbersih.xlsx');
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Terjadi kesalahan saat melakukan export: ' . $e->getMessage()], 500);
    //     }
    // }
}
