<?php

namespace App\Observers;

use App\Models\BayarPiutang;
use App\Models\AccJurnal;
use App\Models\Pelanggan;

class BayarPiutangObserver
{

    public function created(BayarPiutang $bayarPiutang)
    {
        $tipe = 'PP';
        $invoice = $bayarPiutang->invoice;
        $tanggal = $bayarPiutang->tanggal;
        $keterangan = $bayarPiutang->invoice;
        $piutangterbayar = $bayarPiutang->total_bayar + $bayarPiutang->potongan_bayar;
        // piutang > 0
        if ($piutangterbayar > 0) {
            $input3['tipe'] = $tipe; //no edit
            $input3['induk'] = 1;
            $input3['invoice'] = $invoice; //no edit
            $input3['tanggal'] = $tanggal; //no edit
            $input3['kode_acc'] = '1-1210';
            $input3['kode_lawan'] = $bayarPiutang->kode_acc;
            $input3['jumlah'] = $piutangterbayar;
            $input3['posisi'] = 'K';
            $input3['debet'] = 0;
            $input3['kredit'] = $piutangterbayar;
            $input3['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input3);
        }
        //kas > 0
        if ($bayarPiutang->total_bayar > 0) {
            $input1['tipe'] = $tipe; //no edit
            $input1['induk'] = 0;
            $input1['invoice'] = $invoice; //no edit
            $input1['tanggal'] = $tanggal; //no edit
            $input1['kode_acc'] = $bayarPiutang->kode_acc;
            $input1['kode_lawan'] = '1-1210';
            $input1['jumlah'] = $bayarPiutang->total_bayar;
            $input1['posisi'] = 'D';
            $input1['debet'] = $bayarPiutang->total_bayar;
            $input1['kredit'] = 0;
            $input1['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input1);
        }
        // potongan > 0
        if ($bayarPiutang->potongan_bayar > 0) {
            $input2['tipe'] = $tipe; //no edit
            $input2['induk'] = 0;
            $input2['invoice'] = $invoice; //no edit
            $input2['tanggal'] = $tanggal; //no edit
            $input2['kode_acc'] = '7-2100';
            $input2['kode_lawan'] = '1-1210';
            $input2['jumlah'] = $bayarPiutang->potongan_bayar;
            $input2['posisi'] = 'D';
            $input2['debet'] = $bayarPiutang->potongan_bayar;
            $input2['kredit'] = 0;
            $input2['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input2);
        }
        $Pelanggan = Pelanggan::findorfail($bayarPiutang->pelanggan_id);
        if ($Pelanggan) {
            $sisa_piutang = $Pelanggan->piutang - $piutangterbayar;
            $Pelanggan->update(['piutang' => $sisa_piutang]);
        }
    }

    public function updated(BayarPiutang $bayarPiutang)
    {
    }

    public function deleted(BayarPiutang $bayarPiutang)
    {
        AccJurnal::where('invoice', $bayarPiutang->invoice)->delete();
        $Pelanggan = Pelanggan::findorfail($bayarPiutang->pelanggan_id);
        if ($Pelanggan) {
            $sisa_piutang = $Pelanggan->piutang + $bayarPiutang->total_bayar + $bayarPiutang->potongan_bayar;
            $Pelanggan->update(['piutang' => $sisa_piutang]);
        }
    }

    public function restored(BayarPiutang $bayarPiutang)
    {
        //
    }

    public function forceDeleted(BayarPiutang $bayarPiutang)
    {
        //
    }
}
