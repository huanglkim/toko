<?php

namespace App\Traits;

use App\Models\Acc_sa;
use App\Models\Accmutasi;
use App\Models\Accperkiraan;

trait LabaRugi
{
    public function saldoLabarugiBulanan($tahun, $bulan, $kodeAccList, $toko_id, $kelompok)
    {
        $accperkiraans = Accperkiraan::whereIn('kode_acc', $kodeAccList)->where('kelompok', $kelompok)->where('toko_id', $toko_id)->get();

        foreach ($accperkiraans as $accperkiraan) {
            $mutasi = Accmutasi::where('kode_acc', $accperkiraan->kode_acc)->where('tahun', $tahun)->where('bulan', $bulan)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

            $mutasiDebet = $mutasi->total_debet ?? 0;
            $mutasiKredit = $mutasi->total_kredit ?? 0;
            $accperkiraan->saldo = ($mutasiDebet == 0 && $mutasiKredit == 0 ? 0 : $accperkiraan->posisi == 'K') ? $mutasiKredit - $mutasiDebet : $mutasiDebet - $mutasiKredit;
        }

        return $accperkiraans->map(function ($accperkiraan) {
            // Jika saldo tidak ada atau 0, bisa dihapus atau diberikan nilai default
            $accperkiraan->saldo = $accperkiraan->saldo ?? 0;
            return $accperkiraan;
        });
    }

    public function labarugiBulanan($tahun, $bulan, $toko_id)
    {
        $kodeAccList = Accmutasi::where('tahun', $tahun)->where('bulan', $bulan)->where('toko_id', $toko_id)->pluck('kode_acc')->unique();

        $pendapatan = $this->saldoLabarugiBulanan($tahun, $bulan, $kodeAccList, $toko_id, 4);
        $totalPendapatan = $pendapatan->sum('saldo');

        $hpp = $this->saldoLabarugiBulanan($tahun, $bulan, $kodeAccList, $toko_id, 5);
        $totalHpp = $hpp->sum('saldo');

        $biaya = $this->saldoLabarugiBulanan($tahun, $bulan, $kodeAccList, $toko_id, 6);
        $totalBiaya = $biaya->sum('saldo');

        return $totalPendapatan - $totalHpp - $totalBiaya;
    }

    public function labadiTahanSaldoAwal($tahun, $toko_id)
    {
        $totalPendapatan =
            Accmutasi::whereIn('kode_acc', Accperkiraan::where('kelompok', 4)->where('toko_id', $toko_id)->pluck('kode_acc'))
                ->where('tahun', '<', $tahun)
                ->selectRaw('SUM(kredit) - SUM(debet) as total')
                ->value('total') ?? 0;

        $totalHpp =
            Accmutasi::whereIn('kode_acc', Accperkiraan::where('kelompok', 5)->where('toko_id', $toko_id)->pluck('kode_acc'))
                ->where('tahun', '<', $tahun)
                ->selectRaw('SUM(debet) - SUM(kredit) as total')
                ->value('total') ?? 0;

        $totalBiaya =
            Accmutasi::whereIn('kode_acc', Accperkiraan::where('kelompok', 6)->where('toko_id', $toko_id)->pluck('kode_acc'))
                ->where('tahun', '<', $tahun)
                ->selectRaw('SUM(debet) - SUM(kredit) as total')
                ->value('total') ?? 0;

        return $totalPendapatan - $totalHpp - $totalBiaya;
    }

    public function saldoAawal($tahun, $kodeAccList, $toko_id, $kelompok)
    {
        $accperkiraans = Accperkiraan::whereIn('kode_acc', $kodeAccList)->where('kelompok', $kelompok)->where('toko_id', $toko_id)->get();

        foreach ($accperkiraans as $accperkiraan) {
            $mutasi = Accmutasi::where('kode_acc', $accperkiraan->kode_acc)->where('tahun', '<', $tahun)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();

            $mutasiDebet = $mutasi->total_debet ?? 0;
            $mutasiKredit = $mutasi->total_kredit ?? 0;

            $accperkiraan->saldo = $accperkiraan->posisi == 'K' ? $mutasiKredit - $mutasiDebet : $mutasiDebet - $mutasiKredit;
        }

        return $accperkiraans->map(function ($accperkiraan) {
            // Jika saldo tidak ada atau 0, bisa dihapus atau diberikan nilai default
            $accperkiraan->saldo = $accperkiraan->saldo ?? 0;
            return $accperkiraan;
        });
    }

    public function saldoAwalperkiraan($tahun, $toko_id)
    {
        $kodeAccList = Accmutasi::where('tahun', '<', $tahun)->where('toko_id', $toko_id)->pluck('kode_acc')->unique();

        $aktiva = $this->labarugiSaldoawal($tahun, $kodeAccList, $toko_id, 1);
        $ekuitas = $this->labarugiSaldoawal($tahun, $kodeAccList, $toko_id, 2);
        $kewajiban = $this->labarugiSaldoawal($tahun, $kodeAccList, $toko_id, 3);

        return [
            'aktiva' => $aktiva,
            'totalAktiva' => $aktiva->sum('saldo'),
            'ekuitas' => $ekuitas,
            'totalEkuitas' => $ekuitas->sum('saldo'),
            'kewajiban' => $kewajiban,
            'totalKewajiban' => $kewajiban->sum('saldo'),
        ];
    }

    public function labarugiSaldoawal($tahun, $kodeAccList, $toko_id, $kelompok)
    {
        $perkiraans = Accperkiraan::whereIn('kode_acc', $kodeAccList)->where('kelompok', $kelompok)->where('toko_id', $toko_id)->get();

        foreach ($perkiraans as $perkiraan) {
            $mutasi = Accmutasi::where('tahun', '<', $tahun)->where('kode_acc', $perkiraan->kode_acc)->where('toko_id', $toko_id)->get();

            $debet = $mutasi->isEmpty() ? 0 : $mutasi->sum('debet');
            $kredit = $mutasi->isEmpty() ? 0 : $mutasi->sum('kredit');

            Acc_sa::updateOrCreate(
                [
                    'tahun' => $tahun,
                    'toko_id' => $toko_id,
                    'kode_acc' => $perkiraan->kode_acc,
                ],
                [
                    'debet' => $debet,
                    'kredit' => $kredit,
                ],
            );
        }

        return Accperkiraan::where('kelompok', $kelompok)
            ->where('toko_id', $toko_id)
            ->with([
                'accsa' => function ($query) use ($tahun, $toko_id) {
                    $query->where('tahun', $tahun)->where('toko_id', $toko_id);
                },
            ])
            ->get()
            ->map(function ($perkiraan) {
                $sa = $perkiraan->accsa->first();
                $perkiraan->saldo = $perkiraan->posisi == 'D' ? $sa->debet - $sa->kredit : $sa->kredit - $sa->debet;
                return $perkiraan;
            });
    }
}
