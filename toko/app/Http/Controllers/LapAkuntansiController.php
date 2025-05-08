<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Toko;
use App\Models\Accjurnal;

class LapAkuntansiController extends Controller
{
    public function lapjurnalposting()
    {
        return view('lapacc.jurnal');
    }
    public function lpcetakjurnal(Request $request)
    {
        $awal = $request->tanggalawal;
        $from = Date('Y-m-d', strtotime($awal));
        $akhir = $request->tanggalakhir;
        $to = Date('Y-m-d', strtotime($akhir));
        $kode_acc = $request->kode_acc;
        $toko = Toko::find(1);
        $periode = $awal . " s/d " . $akhir;
        $cetak = Accjurnal::where('kode_acc', $kode_acc)
            ->orderBY('created_at', 'ASC')
            ->whereBetween('tanggal', [$from, $to])
            ->get();
        $cetakrk = Accjurnal::whereBetween('tanggal', [$from, $to])
            ->orderBY('created_at', 'ASC')
            ->orderBY('invoice', 'ASC')
            ->orderBY('induk', 'DESC')
            ->get();
        $jenis = $request->jenislaporan;
        $data = [
            'periode' => $periode,
            'from' => $from,
            'to' => $to,
            'toko' => $toko,
            'cetak' => $cetak,
            'cetakrk' => $cetakrk,
            'kode_acc' => $kode_acc
        ];
        if ($jenis == 1) {
            $data['title'] = 'LAPORAN JURNAL KAS/BANK';
            return view('laporan.cetakjurnal', $data);
        }
        if ($jenis == 2) {
            $data['title'] = 'LAPORAN JURNAL MEMORIAL';
            return view('laporan.cetakjurnal', $data);
        }
        if ($jenis == 3) {
            $data['title'] = 'LAPORAN DAFTAR JURNAL';
            return view('lapacc.cetakjurnalpost', $data);
        }
    }
}
