<?php

namespace App\Http\Controllers;

use App\Models\Acc_sa;
use App\Models\Accjurnal;
use App\Models\Accmutasi;
use App\Models\Accperkiraan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BukuBesarExport;

class BukuBesarController extends Controller
{
    public function index()
    {
        $accperkiraans = Accperkiraan::where('tipe', 'D')->get();
        return view('jurnalkhusus.bukubesar', compact('accperkiraans'));
    }

    public function filterbukubesar(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggal_awal = Carbon::parse($request->tanggal_awal)->startOfDay();
        $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

        $accperkiraans = Accperkiraan::whereIn(
            'kode_acc',
            Accjurnal::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                ->pluck('kode_acc')
                ->unique(),
        )->get();

        if ($accperkiraans->isEmpty()) {
            return response()->json(['data' => []]);
        }

        $accperkiraanData = [];

        foreach ($accperkiraans as $accperkiraan) {
            $posisi = $accperkiraan->posisi;
            $kode_acc = $accperkiraan->kode_acc;

            $saldo_awal = $this->hitungSaldoAwal($kode_acc, $posisi, $tanggal_awal);

            $accjurnals = Accjurnal::where('kode_acc', $kode_acc)
                ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                ->get();

            $saldo = $saldo_awal;
            $jurnalData = [];

            foreach ($accjurnals as $accjurnal) {
                $saldo += $posisi === 'D' ? $accjurnal->debet - $accjurnal->kredit : $accjurnal->kredit - $accjurnal->debet;
                $jurnalData[] = [
                    'tanggal' => $accjurnal->tanggal->format('Y-m-d'),
                    'debet' => $accjurnal->debet,
                    'kredit' => $accjurnal->kredit,
                    'saldo' => $saldo,
                ];
            }

            $accperkiraanData[] = [
                'label' => $accperkiraan->nama_acc,
                'saldo_awal' => $saldo_awal,
                'jurnal_data' => $jurnalData,
                'saldo_akhir' => $saldo,
            ];
        }

        return response()->json(['data' => $accperkiraanData]);
    }

    private function hitungSaldoAwal($kode_acc, $posisi, $tanggal_awal)
    {
        $toko_id = 1;
        $saldo_awal = [
            'tahun' => 0,
            'bulan' => 0,
            'hari' => 0,
        ];
        //cari saldo awal tahun
        $tahun = date('Y', strtotime($tanggal_awal));
        $acc_sa = Acc_sa::where('tahun', $tahun)->where('toko_id', $toko_id)->where('kode_acc', $kode_acc)->first();

        if ($acc_sa) {
            $saldo_awal['tahun'] = $posisi == 'D' ? $acc_sa->debet - $acc_sa->kredit : $acc_sa->kredit - $acc_sa->debet;
        }
        //cari saldo awal bulan
        $bulan = date('m', strtotime($tanggal_awal));
        $accmutasi = Accmutasi::where('tahun', $tahun)->where('bulan', '<', $bulan)->where('toko_id', $toko_id)->where('kode_acc', $kode_acc)->get();
        if ($accmutasi->count() > 0) {
            // $saldo_awal['bulan'] = $accmutasi->count();
            $saldo_awal['bulan'] = $posisi == 'D' ? $accmutasi->sum('debet') - $accmutasi->sum('kredit') : $accmutasi->sum('kredit') - $accmutasi->sum('debet');
        }
        //cari saldo awal hari
        $hari = date('d', strtotime($tanggal_awal));
        $accjurnal = Accjurnal::whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->WhereDate('tanggal', '<', $tanggal_awal)->where('toko_id', $toko_id)->where('kode_acc', $kode_acc)->get();

        if ($accjurnal->count() > 0) {
            $saldo_awal['hari'] = $posisi == 'D' ? $accjurnal->sum('debet') - $accjurnal->sum('kredit') : $accjurnal->sum('kredit') - $accjurnal->sum('debet');
        }
        // cari hasil
        $saldo_awal['hasil'] = $saldo_awal['tahun'] + $saldo_awal['bulan'] + $saldo_awal['hari'];

        return $saldo_awal;
    }

    public function printBuku(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'tipe' => 'required|in:cetak,excel',
            'kode_acc' => 'required',
        ]);

        $tanggal_awal = date('Y-m-d', strtotime($request->tanggal_awal));
        $tanggal_akhir = date('Y-m-d', strtotime($request->tanggal_akhir));

        $kode_acc = $request->kode_acc;

        $accperkiraan = Accperkiraan::where('kode_acc', $kode_acc)->first();
        if (!$accperkiraan) {
            return back()->with('error', 'Kode akun tidak ditemukan');
        }

        $posisi = $accperkiraan->posisi;
        // cari saldo awal 1
        $saldo_awal = $this->hitungSaldoAwal($kode_acc, $posisi, $tanggal_awal);

        $accjurnals = Accjurnal::where('kode_acc', $kode_acc)
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->get();

        $saldo = $saldo_awal['hasil'];
        foreach ($accjurnals as $accjurnal) {
            $saldo += $posisi == 'D' ? $accjurnal->debet - $accjurnal->kredit : $accjurnal->kredit - $accjurnal->debet;
            $accjurnal->saldo = $saldo;
        }

        if ($request->tipe == 'cetak') {
            return view('jurnalkhusus.printbukubesar', compact('accjurnals', 'tanggal_awal', 'tanggal_akhir', 'kode_acc', 'accperkiraan', 'posisi', 'saldo_awal'));
        } elseif ($request->tipe == 'excel') {
            return Excel::download(new BukuBesarExport($tanggal_awal, $tanggal_akhir), 'laporan_bukubesar.xlsx');
        }

        return back()->with('error', 'Tipe laporan tidak valid');
    }
}
