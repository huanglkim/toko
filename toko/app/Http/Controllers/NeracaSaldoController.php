<?php

namespace App\Http\Controllers;

use App\Models\Acc_sa;
use App\Models\Accmutasi;
use App\Models\Accperkiraan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NeracaSaldoExport;
use App\Exports\NeracaSaldoTahunanExport;
use Illuminate\Support\Facades\DB;

class NeracaSaldoController extends Controller
{
    public function index()
    {
        return view('jurnalkhusus.neracasaldo');
    }

    // Filter for monthly report
    public function filterneracasaldo(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:m',
            'tahun' => 'required|date_format:Y',
            'toko_id' => 'required|exists:tokos,id',
        ]);

        $bulan = (int) $request->bulan;
        $tahun = (int) $request->tahun;
        $toko_id = $request->toko_id;

        try {
            $kode_accs = Accmutasi::where('tahun', $tahun)->where('bulan', '<=', $bulan)->where('toko_id', $toko_id)->pluck('kode_acc')->unique();

            if ($kode_accs->isEmpty()) {
                return response()->json(['data' => []]);
            }

            $accperkiraans = Accperkiraan::whereIn('kode_acc', $kode_accs)->get();

            $data = [];
            $totalDebet = 0;
            $totalKredit = 0;

            foreach ($accperkiraans as $accperkiraan) {
                $kode_acc = $accperkiraan->kode_acc;

                $saldoTahunAwal = Acc_sa::where('kode_acc', $kode_acc)->where('tahun', $tahun)->where('toko_id', $toko_id)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

                $saldoSebelumnya = Accmutasi::where('kode_acc', $kode_acc)->where('tahun', $tahun)->where('bulan', '<', $bulan)->where('toko_id', $toko_id)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

                $debetAwal = ($saldoTahunAwal->total_debet ?? 0) + ($saldoSebelumnya->total_debet ?? 0);
                $kreditAwal = ($saldoTahunAwal->total_kredit ?? 0) + ($saldoSebelumnya->total_kredit ?? 0);

                $mutasiBulanIni = Accmutasi::where('kode_acc', $kode_acc)->where('tahun', $tahun)->where('bulan', $bulan)->where('toko_id', $toko_id)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

                $debetMutasi = $mutasiBulanIni->total_debet ?? 0;
                $kreditMutasi = $mutasiBulanIni->total_kredit ?? 0;

                $totalDebet += $debetMutasi;
                $totalKredit += $kreditMutasi;

                $data[] = [
                    'kode_acc' => $kode_acc,
                    'nama_acc' => $accperkiraan->nama_acc,
                    'posisi' => $accperkiraan->posisi,
                    'debetAwal' => $debetAwal,
                    'kreditAwal' => $kreditAwal,
                    'debetMutasi' => $debetMutasi,
                    'kreditMutasi' => $kreditMutasi,
                    'saldo_akhir' => $debetAwal - $kreditAwal + $debetMutasi - $kreditMutasi,
                ];
            }

            return response()->json([
                'data' => $data,
                'totalDebet' => $totalDebet,
                'totalKredit' => $totalKredit,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan server.'], 500);
        }
    }

    // Filter for yearly report
    public function filterneracapertahun(Request $request)
    {
        $request->validate([
            'tahun' => 'required|date_format:Y',
            'toko_id' => 'required|exists:tokos,id',
        ]);

        $tahun = $request->input('tahun');
        $toko_id = $request->toko_id;

        $saldo_awal = DB::table('acc_sa')->join('accperkiraan', 'acc_sa.kode_acc', '=', 'accperkiraan.kode_acc')->select('acc_sa.kode_acc', 'accperkiraan.nama_acc', 'accperkiraan.posisi', DB::raw('SUM(acc_sa.debet) as total_debet'), DB::raw('SUM(acc_sa.kredit) as total_kredit'))->where('acc_sa.tahun', '<', $tahun)->groupBy('acc_sa.kode_acc', 'accperkiraan.nama_acc', 'accperkiraan.posisi')->get();

        $mutasi = DB::table('accmutasi')->select('kode_acc', 'nama_acc', 'posisi', DB::raw('SUM(debet) as mutasi_debet'), DB::raw('SUM(kredit) as mutasi_kredit'))->where('tahun', $tahun)->groupBy('kode_acc', 'nama_acc', 'posisi')->get();

        $data = [];
        foreach ($saldo_awal as $sa) {
            $data[$sa->kode_acc] = [
                'kode_acc' => $sa->kode_acc,
                'nama_acc' => $sa->nama_acc,
                'posisi' => $sa->posisi,
                'saldo_awal' => $sa->total_debet - $sa->total_kredit,
                'mutasi_debet' => 0,
                'mutasi_kredit' => 0,
                'saldo_akhir' => 0,
            ];
        }

        foreach ($mutasi as $m) {
            if (!isset($data[$m->kode_acc])) {
                $data[$m->kode_acc] = [
                    'kode_acc' => $m->kode_acc,
                    'nama_acc' => $m->nama_acc,
                    'posisi' => $m->posisi,
                    'saldo_awal' => 0,
                    'mutasi_debet' => $m->mutasi_debet,
                    'mutasi_kredit' => $m->mutasi_kredit,
                    'saldo_akhir' => 0,
                ];
            } else {
                $data[$m->kode_acc]['mutasi_debet'] = $m->mutasi_debet;
                $data[$m->kode_acc]['mutasi_kredit'] = $m->mutasi_kredit;
            }
        }

        foreach ($data as &$item) {
            $item['saldo_akhir'] = $item['saldo_awal'] + $item['mutasi_debet'];
            $item['saldo_akhir'] = $item['saldo_awal'] + $item['mutasi_kredit'];
            $item['saldo_awal'] = 'Rp ' . number_format($item['saldo_awal'], 2, ',', '.');
            $item['mutasi_debet'] = 'Rp ' . number_format($item['mutasi_debet'], 2, ',', '.');
            $item['mutasi_kredit'] = 'Rp ' . number_format($item['mutasi_kredit'], 2, ',', '.');
            $item['saldo_akhir'] = 'Rp ' . number_format($item['saldo_akhir'], 2, ',', '.');
        }

        return response()->json(array_values($data));
    }

    // Print monthly report
    public function printBulanan(Request $request)
    {
        $bulan = $request->input('bulan', '01');
        $tahun = $request->input('tahun');
        $toko_id = 1;

        $kodeAkunList = Acc_sa::where('tahun', $tahun)
            ->where('toko_id', $toko_id)
            ->pluck('kode_acc')
            ->merge(Accmutasi::where('tahun', $tahun)->where('toko_id', $toko_id)->pluck('kode_acc'))
            ->unique();

        $accmutasis = collect($kodeAkunList)
            ->map(function ($kode_acc) use ($bulan, $tahun, $toko_id) {
                $accperkiraan = Accperkiraan::where('kode_acc', $kode_acc)->first();

                if (!$accperkiraan) {
                    return null;
                }

                $saldoSebelumnya = Acc_sa::where('kode_acc', $kode_acc)->where('tahun', $tahun)->where('toko_id', $toko_id)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

                $mutasiSebelumnya = Accmutasi::where('kode_acc', $kode_acc)->where('bulan', '<', $bulan)->where('tahun', $tahun)->where('toko_id', $toko_id)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

                $debetAwal = ($saldoSebelumnya->total_debet ?? 0) + ($mutasiSebelumnya->total_debet ?? 0);
                $kreditAwal = ($saldoSebelumnya->total_kredit ?? 0) + ($mutasiSebelumnya->total_kredit ?? 0);

                $mutasiSekarang = Accmutasi::where('kode_acc', $kode_acc)->where('bulan', $bulan)->where('tahun', $tahun)->where('toko_id', $toko_id)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

                $debetMutasi = $mutasiSekarang->total_debet ?? 0;
                $kreditMutasi = $mutasiSekarang->total_kredit ?? 0;

                $debetAkhir = $debetAwal + $debetMutasi;
                $kreditAkhir = $kreditAwal + $kreditMutasi;

                return [
                    'kode_acc' => $kode_acc,
                    'nama_acc' => $accperkiraan->nama_acc,
                    'posisi' => $accperkiraan->posisi,
                    'debetAwal' => $debetAwal,
                    'kreditAwal' => $kreditAwal,
                    'debetMutasi' => $debetMutasi,
                    'kreditMutasi' => $kreditMutasi,
                    'debetAkhir' => $debetAkhir,
                    'kreditAkhir' => $kreditAkhir,
                ];
            })
            ->filter();

        return view('jurnalkhusus.printneracasaldo', compact('accmutasis', 'bulan', 'tahun'));
    }

    // Print yearly report
    public function printTahunan(Request $request)
    {
        $tahun = $request->input('tahun');
        $toko_id = 1;

        $kodeAkunList = Acc_sa::where('tahun', '<=', $tahun)
            ->where('toko_id', $toko_id)
            ->pluck('kode_acc')
            ->merge(Accmutasi::where('tahun', '<=', $tahun)->where('toko_id', $toko_id)->pluck('kode_acc'))
            ->unique();

        $accmutasis = collect($kodeAkunList)
            ->map(function ($kode_acc) use ($tahun, $toko_id) {
                $accperkiraan = Accperkiraan::where('kode_acc', $kode_acc)->first();

                if (!$accperkiraan) {
                    return null;
                }

                $saldoSebelumnya = Acc_sa::where('kode_acc', $kode_acc)->where('tahun', $tahun)->where('toko_id', $toko_id)->selectRaw('COALESCE(SUM(debet), 0) as total_debet, COALESCE(SUM(kredit), 0) as total_kredit')->first();

                $mutasiSebelumnya = Accmutasi::where('kode_acc', $kode_acc)->where('tahun', '<', $tahun)->where('toko_id', $toko_id)->selectRaw('COALESCE(SUM(debet), 0) as total_debet, COALESCE(SUM(kredit), 0) as total_kredit')->first();

                $debetAwal = ($saldoSebelumnya->total_debet ?? 0) + ($mutasiSebelumnya->total_debet ?? 0);
                $kreditAwal = ($saldoSebelumnya->total_kredit ?? 0) + ($mutasiSebelumnya->total_kredit ?? 0);

                $mutasiSekarang = Accmutasi::where('kode_acc', $kode_acc)->where('tahun', $tahun)->where('toko_id', $toko_id)->selectRaw('COALESCE(SUM(debet), 0) as total_debet, COALESCE(SUM(kredit), 0) as total_kredit')->first();

                $debetMutasi = $mutasiSekarang->total_debet ?? 0;
                $kreditMutasi = $mutasiSekarang->total_kredit ?? 0;

                $debetAkhir = $debetAwal + $debetMutasi;
                $kreditAkhir = $kreditAwal + $kreditMutasi;

                return [
                    'kode_acc' => $kode_acc,
                    'nama_acc' => $accperkiraan->nama_acc,
                    'posisi' => $accperkiraan->posisi,
                    'debetAwal' => $debetAwal,
                    'kreditAwal' => $kreditAwal,
                    'debetMutasi' => $debetMutasi,
                    'kreditMutasi' => $kreditMutasi,
                    'debetAkhir' => $debetAkhir,
                    'kreditAkhir' => $kreditAkhir,
                ];
            })
            ->filter();

        return view('jurnalkhusus.printneracasaldotahunan', compact('tahun', 'accmutasis'));
    }

    // Export to Excel for both monthly and yearly reports
    public function exportToExcelNeraca(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $toko_id = $request->input('toko_id');

        // For monthly report
        return Excel::download(new NeracaSaldoExport($bulan, $tahun, $toko_id), 'neraca-saldo-bulanan.xlsx');
    }

    public function exportToExcelTahun(Request $request)
    {
        $tahun = $request->input('tahun');
        $toko_id = $request->input('toko_id');

        // For yearly report
        return Excel::download(new NeracaSaldoTahunanExport($tahun, $toko_id), 'neraca-saldo-tahunan.xlsx');
    }
}
