<?php

namespace App\Observers;

use App\Models\BayarHutang;
use App\Models\AccJurnal;
use App\Models\Suplier;

class BayarHutangObserver
{

    public function created(BayarHutang $bayarHutang)
    {
        $tipe = 'PH';
        $invoice = $bayarHutang->invoice;
        $tanggal = $bayarHutang->tanggal;
        $keterangan = $bayarHutang->invoice;
        $hutangterbayar = $bayarHutang->total_bayar + $bayarHutang->potongan_bayar;
        // hutang > 0
        if ($hutangterbayar > 0) {
            $input3['tipe'] = $tipe; //no edit
            $input3['induk'] = 1;
            $input3['invoice'] = $invoice; //no edit
            $input3['tanggal'] = $tanggal; //no edit
            $input3['kode_acc'] = '2-1101';
            $input3['kode_lawan'] = $bayarHutang->kode_acc;
            $input3['jumlah'] = $hutangterbayar;
            $input3['posisi'] = 'D';
            $input3['debet'] = $hutangterbayar;
            $input3['kredit'] = 0;
            $input3['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input3);
        }
        //kas > 0
        if ($bayarHutang->total_bayar > 0) {
            $input1['tipe'] = $tipe; //no edit
            $input1['induk'] = 0;
            $input1['invoice'] = $invoice; //no edit
            $input1['tanggal'] = $tanggal; //no edit
            $input1['kode_acc'] = $bayarHutang->kode_acc;
            $input1['kode_lawan'] = '2-1101';
            $input1['jumlah'] = $bayarHutang->total_bayar;
            $input1['posisi'] = 'K';
            $input1['debet'] = 0;
            $input1['kredit'] = $bayarHutang->total_bayar;
            $input1['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input1);
        }
        // potongan > 0
        if ($bayarHutang->potongan_bayar > 0) {
            $input2['tipe'] = $tipe; //no edit
            $input2['induk'] = 0;
            $input2['invoice'] = $invoice; //no edit
            $input2['tanggal'] = $tanggal; //no edit
            $input2['kode_acc'] = '7-1100';
            $input2['kode_lawan'] = '2-1101';
            $input2['jumlah'] = $bayarHutang->potongan_bayar;
            $input2['posisi'] = 'K';
            $input2['debet'] = 0;
            $input2['kredit'] = $bayarHutang->potongan_bayar;
            $input2['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input2);
        }
        $Suplier = Suplier::findorfail($bayarHutang->suplier_id);
        if ($Suplier) {
            $sisa_hutang = $Suplier->hutang - $hutangterbayar;
            $Suplier->update(['hutang' => $sisa_hutang]);
        }
    }

    public function updated(BayarHutang $bayarHutang)
    {
    }

    public function deleted(BayarHutang $bayarHutang)
    {
        AccJurnal::where('invoice', $bayarHutang->invoice)->delete();
        $Suplier = Suplier::findorfail($bayarHutang->suplier_id);
        if ($Suplier) {
            $sisa_hutang = $Suplier->hutang + $bayarHutang->total_bayar + $bayarHutang->potongan_bayar;
            $Suplier->update(['hutang' => $sisa_hutang]);
        }
    }

    public function restored(BayarHutang $bayarHutang)
    {
        //
    }

    public function forceDeleted(BayarHutang $bayarHutang)
    {
        //
    }
}
