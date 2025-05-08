<?php

namespace App\Http\Controllers;

use App\Models\Acc_sa;
use App\Models\Accperkiraan;
use App\Models\Accmutasi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NeracaExport;
use App\Traits\LabaRugi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NeracaController extends Controller
{
    use LabaRugi;

    public function index()
    {
        return view('jurnalkhusus.neraca');
    }

    private function labatahunberjalan($bulan, $tahun)
    {
        $toko_id = 1;
        $labarugiditahan = 0;
        $arraybulan = range(1, $bulan);
        foreach ($arraybulan as $month) {
            $labarugiditahan += $this->labarugiBulanan($tahun, $month, $toko_id);
        }
        return $labarugiditahan;
    }

    public function printNeraca(Request $request)
    {
        $bulan = (int) $request->input('bulan', 1);
        $tahun = (int) $request->input('tahun');
        $toko_id = 1;

        Log::info("NeracaController: bulan = {$bulan}, tahun = {$tahun}");

        $kodeAccList = Acc_sa::where('tahun', $tahun)
            ->where('toko_id', $toko_id)
            ->pluck('kode_acc')
            ->merge(
                Accmutasi::where('tahun', $tahun)
                    ->where('toko_id', $toko_id)
                    ->pluck('kode_acc')
            )
            ->unique();

        Log::info("NeracaController: kodeAccList = " . json_encode($kodeAccList));

        $daftarAkun = Accperkiraan::whereIn('kode_acc', $kodeAccList)
            ->whereIn('kelompok', [1, 2, 3])
            ->get()
            ->keyBy('kode_acc');

        Log::info("NeracaController: daftarAkun = " . json_encode($daftarAkun));

        $saldoAwal = Acc_sa::whereIn('kode_acc', $kodeAccList)
            ->where('tahun', '<', $tahun)
            ->where('toko_id', $toko_id)
            ->select('kode_acc', DB::raw('SUM(debet) as total_debet'), DB::raw('SUM(kredit) as total_kredit'))
            ->groupBy('kode_acc')
            ->get()
            ->keyBy('kode_acc');

        Log::info("NeracaController: saldoAwal = " . json_encode($saldoAwal));

       $mutasi = Accmutasi::whereIn('kode_acc', $kodeAccList)
    ->where('tahun', $tahun)
    ->where('toko_id', $toko_id)
    ->whereBetween('bulan', [1, $bulan])
    ->select('kode_acc', DB::raw('SUM(debet) as total_debet'), DB::raw('SUM(kredit) as total_kredit'))
    ->groupBy('kode_acc')
    ->get()
    ->keyBy('kode_acc');


        Log::info("NeracaController: mutasi = " . json_encode($mutasi));

        $aktiva = [];
        $kewajiban = [];

        foreach ($daftarAkun as $kode => $akun) {
            $saldoAwalAkun = $saldoAwal->get($kode);
            $mutasiAkun = $mutasi->get($kode);

            $totalDebet = ($saldoAwalAkun->total_debet ?? 0) + ($mutasiAkun->total_debet ?? 0);
            $totalKredit = ($saldoAwalAkun->total_kredit ?? 0) + ($mutasiAkun->total_kredit ?? 0);

            if ($akun->kelompok == 1) {
                $akun->saldo = $totalDebet - $totalKredit;
                $aktiva[] = $akun;
            } elseif (in_array($akun->kelompok, [2, 3])) {
                $akun->saldo = $totalKredit - $totalDebet;
                $kewajiban[] = $akun;
            }
        }

        $labatahunberjalan = $this->labatahunberjalan($bulan, $tahun);
        Log::info("NeracaController: labatahunberjalan = {$labatahunberjalan}");

        $saldoAwalLabaDitahan = Acc_sa::where('tahun', $tahun)
            ->where('kode_acc', '3-2000')
            ->where('toko_id', $toko_id)
            ->value('kredit') ?? 0;

        Log::info("NeracaController: saldoAwalLabaDitahan = {$saldoAwalLabaDitahan}");

        $mutasiLabaDitahan = Accmutasi::where('tahun', $tahun)
            ->where('kode_acc', '3-2000')
            ->where('toko_id', $toko_id)
            ->whereBetween('bulan', [1, $bulan])
            ->select(DB::raw('SUM(debet) as total_debet'), DB::raw('SUM(kredit) as total_kredit'))
            ->first();

        Log::info("NeracaController: mutasiLabaDitahan = " . json_encode($mutasiLabaDitahan));

        $totalDebetLabaDitahan = $mutasiLabaDitahan->total_debet ?? 0;
        $totalKreditLabaDitahan = $mutasiLabaDitahan->total_kredit ?? 0;
        $labaditahan = $saldoAwalLabaDitahan + ($totalKreditLabaDitahan - $totalDebetLabaDitahan);

        Log::info("NeracaController: labaditahan = {$labaditahan}");

        $totalAktiva = collect($aktiva)->sum('saldo');
        $totalKewajiban = collect($kewajiban)->sum('saldo');
        Log::info("NeracaController: totalAktiva = {$totalAktiva}, totalKewajiban = {$totalKewajiban}");

        return view('jurnalkhusus.printneraca', [
            'aktiva' => $aktiva,
            'kewajiban' => $kewajiban,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'labatahunberjalan' => $labatahunberjalan,
            'labaditahan' => $labaditahan,
            'totalAktiva' => $totalAktiva,
            'totalKewajiban' => $totalKewajiban,
        ]);
    }

    public function exportToExcelNeraca(Request $request)
    {
        $bulan = (int) $request->input('bulan', 1);
        $tahun = (int) $request->input('tahun');
        $toko_id = 1;

        $kodeAccList = Acc_sa::where('tahun', $tahun)
            ->where('toko_id', $toko_id)
            ->pluck('kode_acc')
            ->merge(Accmutasi::where('tahun', $tahun)->where('toko_id', $toko_id)->pluck('kode_acc'))
            ->unique();

        $saldoAwalLabaDitahan = Acc_sa::where('tahun', $tahun)->where('kode_acc', '3-0000')->where('toko_id', $toko_id)->value('kredit') ?? 0;

        $mutasiLabaDitahan = Accmutasi::where('tahun', $tahun)
            ->where('kode_acc', '3-0000')
            ->where('toko_id', $toko_id)
            ->whereBetween('bulan', [1, $bulan])
            ->select(DB::raw('SUM(debet) as total_debet'), DB::raw('SUM(kredit) as total_kredit'))
            ->first();

        $totalDebetLabaDitahan = $mutasiLabaDitahan->total_debet ?? 0;
        $totalKreditLabaDitahan = $mutasiLabaDitahan->total_kredit ?? 0;
        $labaditahan = $saldoAwalLabaDitahan + ($totalKreditLabaDitahan - $totalDebetLabaDitahan);

        return Excel::download(new NeracaExport($bulan, $tahun, $labaditahan), 'Neraca_Laporan.xlsx');
    }
}