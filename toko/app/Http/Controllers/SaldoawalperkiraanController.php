<?php

namespace App\Http\Controllers;

use App\Models\{Acc_sa, Accperkiraan, Accmutasi};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaldoawalperkiraanController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun') ?? date('Y');
        return $this->tampilkanSaldoAwal($tahun);
    }

    public function saldoaccsa(Request $request)
    {
        Log::info('>> MASUK saldoaccsa');

        $tahun = $request->input('tahun');
        $toko_id = 1;

        $alreadyExist = Acc_sa::where('tahun', $tahun)->where('toko_id', $toko_id)->exists();

        Log::info('>> CEK alreadyExist', ['exists' => $alreadyExist]);

        if (!$alreadyExist) {
            $kodeAccList = Accperkiraan::where('toko_id', $toko_id)
                ->whereNotIn('kode_acc', ['3-2000', '3-3000'])
                ->pluck('kode_acc');

            // Generate saldo awal untuk kelompok 1, 2, dan 3 (kecuali 3-2000 dan 3-3000)
            foreach ([1, 2, 3] as $kelompok) {
                Log::info('Memproses kelompok', ['kelompok' => $kelompok]);
                $this->generateSaldoAwal($tahun, $toko_id, $kelompok);
            }

            // Hitung dan simpan saldo awal untuk Laba Ditahan (3-2000)
            $kreditLabaditahan = $this->hitungLabaDitahan($tahun, $toko_id);
            Acc_sa::create([
                'tahun' => $tahun,
                'toko_id' => $toko_id,
                'kode_acc' => '3-2000',
                'debet' => 0,
                'kredit' => $kreditLabaditahan,
            ]);


            // Acc_sa::create([
            //     'tahun' => $tahun,
            //     'toko_id' => $toko_id,
            //     'kode_acc' => '3-3000',
            //     'debet' => 0,
            //     'kredit' => 0,
            // ]);
        }
    
        Log::info('Cek saldo awal sudah ada atau belum', ['exists' => $alreadyExist]);
        return response()->json([
            'redirect_url' => route('saldoawal.index', ['tahun' => $request->tahun]),
        ]);
    }

    private function generateSaldoAwal($tahun, $toko_id, $kelompok)
    {
        $akunList = Accperkiraan::where('toko_id', $toko_id)
            ->where('kelompok', $kelompok)
            ->whereNotIn('kode_acc', ['3-2000', '3-3000'])
            ->get();

        Log::info("Kelompok $kelompok", [
            'jumlah_akun' => $akunList->count(),
            'akunList' => $akunList->pluck('kode_acc'),
        ]);

        foreach ($akunList as $akun) {
            $mutasi = Accmutasi::where('kode_acc', $akun->kode_acc)->where('toko_id', $toko_id)->where('tahun', '<', $tahun)->get();

            Log::info('Mutasi ditemukan', [
                'kode_acc' => $akun->kode_acc,
                'jumlah_mutasi' => $mutasi->count(),
                'debet' => $mutasi->sum('debet'),
                'kredit' => $mutasi->sum('kredit'),
            ]);

            $totalDebet = $mutasi->sum('debet') ?? 0;
            $totalKredit = $mutasi->sum('kredit') ?? 0;

            $debet = 0;
            $kredit = 0;

            if ($akun->posisi == 'D') {
                $saldo = $totalDebet - $totalKredit;
                $debet = $saldo > 0 ? $saldo : 0;
                $kredit = $saldo < 0 ? abs($saldo) : 0;
            } elseif ($akun->posisi == 'K') {
                $saldo = $totalKredit - $totalDebet;
                $debet = $saldo < 0 ? abs($saldo) : 0;
                $kredit = $saldo > 0 ? $saldo : 0;
            }

            Log::info('Menyimpan acc_sa', [
                'kode_acc' => $akun->kode_acc,
                'debet' => $debet,
                'kredit' => $kredit,
            ]);

            Acc_sa::create([
                'tahun' => $tahun,
                'toko_id' => $toko_id,
                'kode_acc' => $akun->kode_acc,
                'debet' => $debet,
                'kredit' => $kredit,
            ]);
        }
    }

    private function hitungLabaDitahan($tahun, $toko_id)
    {
        $mutasi = Accmutasi::where('toko_id', $toko_id)->where('tahun', '<', $tahun)->get();

        $pendapatan = $mutasi->filter(fn($m) => substr($m->kode_acc, 0, 1) == '4')->sum('kredit') ?? 0; // Tambahkan null coalescing operator
        $biaya = $mutasi->filter(fn($m) => substr($m->kode_acc, 0, 1) == '5')->sum('debet') ?? 0; // Tambahkan null coalescing operator

        return $pendapatan - $biaya;
    }

    private function tampilkanSaldoAwal($tahun)
    {
        $toko_id = 1;

        $dataSa = Acc_sa::where('tahun', $tahun)->where('toko_id', $toko_id)->get()->keyBy('kode_acc');

        $aktiva = Accperkiraan::where('kelompok', 1)
            ->where('toko_id', $toko_id)
            ->with(['accsa' => fn($q) => $q->where('tahun', $tahun)->where('toko_id', $toko_id)])
            ->get();

        $kewajiban = Accperkiraan::whereIn('kelompok', [2, 3])
            ->whereNotIn('kode_acc', ['3-2000', '3-3000'])
            ->where('toko_id', $toko_id)
            ->with(['accsa' => fn($q) => $q->where('tahun', $tahun)->where('toko_id', $toko_id)])
            ->get();

        $saldoAwal = ['aktiva' => [], 'kewajiban' => []];

        $saldoTotalAktiva = 0;
        foreach ($aktiva as $akun) {
            $sa = $akun->accsa;
            $debet = $sa->first()->debet ?? 0; // Gunakan first() karena with() mengembalikan Collection
            $kredit = $sa->first()->kredit ?? 0; // Gunakan first() karena with() mengembalikan Collection
            $saldo = $akun->posisi == 'D' ? $debet - $kredit : $kredit - $debet;
            $akun->saldo = $saldo;
            $saldoAwal['aktiva'][$akun->kode_acc] = $saldo;
            $saldoTotalAktiva += $saldo;
        }

        $saldoTotalKewajiban = 0;
        foreach ($kewajiban as $akun) {
            $sa = $akun->accsa;
            $debet = $sa->first()->debet ?? 0; // Gunakan first() karena with() mengembalikan Collection
            $kredit = $sa->first()->kredit ?? 0; // Gunakan first() karena with() mengembalikan Collection
            $saldo = $akun->posisi == 'D' ? $debet - $kredit : $kredit - $debet;
            $akun->saldo = $saldo;
            $saldoAwal['kewajiban'][$akun->kode_acc] = $saldo;
            $saldoTotalKewajiban += $saldo;
        }

        $labatahunberjalan = $this->hitungLabaDitahan($tahun, $toko_id);
        $labaditahanSa = $dataSa['3-2000'] ?? null;
        $labaditahan = $labaditahanSa ? $labaditahanSa->kredit ?? 0 : 0;
        $saldoTotalKewajiban += $labaditahan;

        $seimbang = abs($saldoTotalAktiva - $saldoTotalKewajiban) < 0.001; // Gunakan toleransi untuk floating point
        $selisih = $saldoTotalAktiva - $saldoTotalKewajiban;
        $sudahDiproses = Acc_sa::where('tahun', $tahun)->where('toko_id', $toko_id)->exists();

        return view('jurnalkhusus.saldoawal', [
            'aktiva' => $aktiva,
            'kewajiban' => $kewajiban,
            'tahun' => $tahun,
            'labatahunberjalan' => $labatahunberjalan,
            'labaditahan' => $labaditahan,
            'totalAktiva' => $saldoTotalAktiva,
            'totalKewajiban' => $saldoTotalKewajiban,
            'saldoAwal' => $saldoAwal,
            'seimbang' => $seimbang,
            'selisih' => $selisih,
            'sudahDiproses' => $sudahDiproses,
        ]);
    }
}
