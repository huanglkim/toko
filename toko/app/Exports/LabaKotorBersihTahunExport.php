<?php

namespace App\Exports;

use App\Models\Accperkiraan;
use App\Models\Accmutasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LabaKotorBersihTahunExport implements FromCollection, WithTitle
{
    protected $bulan;
    protected $tahun;
    protected $toko_id;

    public function __construct($bulan, $tahun, $toko_id)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->toko_id = $toko_id;
    }

    public function collection()
    {
        $bulan = $this->bulan;
        $tahun = $this->tahun;
        $toko_id = $this->toko_id;

        $data = [];
        $data[] = ['Kode Akun', 'Nama Akun', 'Saldo']; // Header

        $kodeAccList = Accmutasi::where('tahun', $tahun)->where('bulan', $bulan)->where('toko_id', $toko_id)->pluck('kode_acc')->unique();

        $pendapatan = Accperkiraan::whereIn('kode_acc', $kodeAccList)->where('kelompok', 4)->get();
        $hpp = Accperkiraan::whereIn('kode_acc', $kodeAccList)->where('kelompok', 5)->get();
        $biaya = Accperkiraan::whereIn('kode_acc', $kodeAccList)->where('kelompok', 6)->get();

        foreach ($pendapatan as $akun) {
            $mutasi = Accmutasi::where('kode_acc', $akun->kode_acc)->where('tahun', $tahun)->where('bulan', $bulan)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();
            $saldo = $mutasi ? $mutasi->total_kredit - $mutasi->total_debet : 0;
            $data[] = [$akun->kode_acc, $akun->nama_acc, $saldo];
        }

        foreach ($hpp as $akun) {
            $mutasi = Accmutasi::where('kode_acc', $akun->kode_acc)->where('tahun', $tahun)->where('bulan', $bulan)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();
            $saldo = $mutasi ? $mutasi->total_debet - $mutasi->total_kredit : 0;
            $data[] = [$akun->kode_acc, $akun->nama_acc, $saldo];
        }

        foreach ($biaya as $akun) {
            $mutasi = Accmutasi::where('kode_acc', $akun->kode_acc)->where('tahun', $tahun)->where('bulan', $bulan)->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')->first();
            $saldo = $mutasi ? $mutasi->total_debet - $mutasi->total_kredit : 0;
            $data[] = [$akun->kode_acc, $akun->nama_acc, $saldo];
        }

        return new Collection($data);
    }

    public function title(): string
    {
        return 'Laba Rugi';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}