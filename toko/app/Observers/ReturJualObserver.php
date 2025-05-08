<?php

namespace App\Observers;

use App\Models\ReturJual;
use App\Models\AccJurnal;

class ReturJualObserver
{

    public function created(ReturJual $returJual)
    {
        $tipe = $returJual->tipe;
        $invoice = $returJual->invoice;
        $tanggal = $returJual->tanggal;
        $keterangan = $returJual->invoice;
        //kas > 0
        if ($returJual->kas > 0) {
            $input1['tipe'] = $tipe; //no edit
            $input1['induk'] = 1;
            $input1['invoice'] = $invoice; //no edit
            $input1['tanggal'] = $tanggal; //no edit
            $input1['kode_acc'] = $returJual->kode_kas;
            $input1['kode_lawan'] = '1-1301';
            $input1['jumlah'] = $returJual->kas;
            $input1['posisi'] = 'K';
            $input1['debet'] = 0;
            $input1['kredit'] = $returJual->kas;
            $input1['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input1);
        }
        // bank > 0
        if ($returJual->bank > 0) {
            $input2['tipe'] = $tipe; //no edit
            $input2['induk'] = 1;
            $input2['invoice'] = $invoice; //no edit
            $input2['tanggal'] = $tanggal; //no edit
            $input2['kode_acc'] = $returJual->kode_bank;
            $input2['kode_lawan'] = '1-1301';
            $input2['jumlah'] = $returJual->bank;
            $input2['posisi'] = 'K';
            $input2['debet'] = 0;
            $input2['kredit'] = $returJual->bank;
            $input2['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input2);
        }
        // piutang > 0
        if ($returJual->piutang > 0) {
            $input3['tipe'] = $tipe; //no edit
            $input3['induk'] = 1;
            $input3['invoice'] = $invoice; //no edit
            $input3['tanggal'] = $tanggal; //no edit
            $input3['kode_acc'] = $returJual->kode_piutang;
            $input3['kode_lawan'] = '1-1301';
            $input3['jumlah'] = $returJual->piutang;
            $input3['posisi'] = 'K';
            $input3['debet'] = 0;
            $input3['kredit'] = $returJual->piutang;
            $input3['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input3);
        }
        // ppn > 0
        if ($returJual->ppn > 0) {
            $input4['tipe'] = $tipe; //no edit
            $input4['induk'] = 0;
            $input4['invoice'] = $invoice; //no edit
            $input4['tanggal'] = $tanggal; //no edit
            $input4['kode_acc'] = '2-4110';
            $input4['kode_lawan'] = '1-1301';
            $input4['jumlah'] = $returJual->ppn;
            $input4['posisi'] = 'D';
            $input4['debet'] = $returJual->ppn;
            $input4['kredit'] = 0;
            $input4['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input4);
        }
        //total_hpp //
        if ($returJual->total_hpp > 0) {
            $input5['tipe'] = $tipe; //no edit
            $input5['induk'] = 0;
            $input5['invoice'] = $invoice; //no edit
            $input5['tanggal'] = $tanggal; //no edit
            $input5['kode_acc'] = '1-1301';
            $input5['kode_lawan'] = '5-1100';
            $input5['jumlah'] = $returJual->total_hpp;
            $input5['posisi'] = 'D';
            $input5['debet'] = $returJual->total_hpp;
            $input5['kredit'] = 0;
            $input5['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input5);

            $input6['tipe'] = $tipe; //no edit
            $input6['induk'] = 0;
            $input6['invoice'] = $invoice; //no edit
            $input6['tanggal'] = $tanggal; //no edit
            $input6['kode_acc'] = '5-1100';
            $input6['kode_lawan'] = '1-1301';
            $input6['jumlah'] = $returJual->total_hpp;
            $input6['posisi'] = 'K';
            $input6['debet'] = 0;
            $input6['kredit'] = $returJual->total_hpp;
            $input6['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input6);
        }
        // pendapatan_barang
        if ($returJual->pendapatan_barang > 0) {
            $input7['tipe'] = $tipe; //no edit
            $input7['induk'] = 0;
            $input7['invoice'] = $invoice; //no edit
            $input7['tanggal'] = $tanggal; //no edit
            $input7['kode_acc'] = '4-1100';
            $input7['kode_lawan'] = $returJual->kode_kas;
            $input7['jumlah'] = $returJual->pendapatan_barang;
            $input7['posisi'] = 'D';
            $input7['debet'] = $returJual->pendapatan_barang;
            $input7['kredit'] = 0;
            $input7['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input7);
        }
    }

    public function updated(ReturJual $returJual)
    {
        $tipe = $returJual->tipe;
        $invoice = $returJual->invoice;
        $tanggal = $returJual->tanggal;
        $keterangan = $returJual->invoice;
        //kas > 0
        if ($returJual->kas != $returJual->getOriginal('kas')) {
            AccJurnal::where('invoice', $returJual->invoice)
                ->where('kode_acc', $returJual->getOriginal('kode_kas'))
                ->delete();
            if ($returJual->kas > 0) {
                $input1['tipe'] = $tipe; //no edit
                $input1['induk'] = 1;
                $input1['invoice'] = $invoice; //no edit
                $input1['tanggal'] = $tanggal; //no edit
                $input1['kode_acc'] = $returJual->kode_kas;
                $input1['kode_lawan'] = '1-1301';
                $input1['jumlah'] = $returJual->kas;
                $input1['posisi'] = 'K';
                $input1['debet'] = 0;
                $input1['kredit'] = $returJual->kas;
                $input1['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input1);
            }
        }
        // bank > 0
        if ($returJual->bank != $returJual->getOriginal('bank')) {
            AccJurnal::where('invoice', $returJual->invoice)
                ->where('kode_acc', $returJual->getOriginal('kode_bank'))
                ->delete();
            if ($returJual->bank > 0) {
                $input2['tipe'] = $tipe; //no edit
                $input2['induk'] = $returJual->kas > 0 ? 0 : 1;
                $input2['invoice'] = $invoice; //no edit
                $input2['tanggal'] = $tanggal; //no edit
                $input2['kode_acc'] = $returJual->kode_bank;
                $input2['kode_lawan'] = '1-1301';
                $input2['jumlah'] = $returJual->bank;
                $input2['posisi'] = 'K';
                $input2['debet'] = 0;
                $input2['kredit'] = $returJual->bank;
                $input2['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input2);
            }
        }
        // piutang > 0
        if ($returJual->piutang != $returJual->getOriginal('piutang')) {
            AccJurnal::where('invoice', $returJual->invoice)
                ->where('kode_acc', $returJual->getOriginal('kode_piutang'))
                ->delete();
            if ($returJual->piutang > 0) {
                $input3['tipe'] = $tipe; //no edit
                $input2['induk'] = $returJual->kas > 0 ? 0 : 1;
                $input3['invoice'] = $invoice; //no edit
                $input3['tanggal'] = $tanggal; //no edit
                $input3['kode_acc'] = $returJual->kode_piutang;
                $input3['kode_lawan'] = '1-1301';
                $input3['jumlah'] = $returJual->piutang;
                $input3['posisi'] = 'K';
                $input3['debet'] = 0;
                $input3['kredit'] = $returJual->piutang;
                $input3['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input3);
            }
        }
        // ppn > 0
        if ($returJual->ppn != $returJual->getOriginal('ppn')) {
            AccJurnal::where('invoice', $returJual->invoice)
                ->where('kode_acc', '2-4110')
                ->delete();
            if ($returJual->ppn > 0) {
                $input4['tipe'] = $tipe; //no edit
                $input4['induk'] = 0;
                $input4['invoice'] = $invoice; //no edit
                $input4['tanggal'] = $tanggal; //no edit
                $input4['kode_acc'] = '2-4110';
                $input4['kode_lawan'] = '1-1301';
                $input4['jumlah'] = $returJual->ppn;
                $input4['posisi'] = 'D';
                $input4['debet'] = $returJual->ppn;
                $input4['kredit'] = 0;
                $input4['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input4);
            }
        }
        //total_hpp //
        if ($returJual->total_hpp != $returJual->getOriginal('total_hpp')) {
            AccJurnal::where('invoice', $returJual->invoice)
                ->whereIn('kode_acc', ['1-1301', '5-1100'])
                ->delete();
            if ($returJual->total_hpp > 0) {
                $input5['tipe'] = $tipe; //no edit
                $input5['induk'] = 0;
                $input5['invoice'] = $invoice; //no edit
                $input5['tanggal'] = $tanggal; //no edit
                $input5['kode_acc'] = '1-1301';
                $input5['kode_lawan'] = '5-1100';
                $input5['jumlah'] = $returJual->total_hpp;
                $input5['posisi'] = 'D';
                $input5['debet'] = $returJual->total_hpp;
                $input5['kredit'] = 0;
                $input5['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input5);

                $input6['tipe'] = $tipe; //no edit
                $input6['induk'] = 0;
                $input6['invoice'] = $invoice; //no edit
                $input6['tanggal'] = $tanggal; //no edit
                $input6['kode_acc'] = '5-1100';
                $input6['kode_lawan'] = '1-1301';
                $input6['jumlah'] = $returJual->total_hpp;
                $input6['posisi'] = 'K';
                $input6['debet'] = 0;
                $input6['kredit'] = $returJual->total_hpp;
                $input6['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input6);
            }
        }
        // pendapatan_barang
        if ($returJual->pendapatan_barang != $returJual->getOriginal('pendapatan_barang')) {
            AccJurnal::where('invoice', $returJual->invoice)
                ->where('kode_acc', '4-1100')
                ->delete();
            if ($returJual->pendapatan_barang > 0) {
                $input7['tipe'] = $tipe; //no edit
                $input7['induk'] = 0;
                $input7['invoice'] = $invoice; //no edit
                $input7['tanggal'] = $tanggal; //no edit
                $input7['kode_acc'] = '4-1100';
                $input7['kode_lawan'] = $returJual->kode_kas;
                $input7['jumlah'] = $returJual->pendapatan_barang;
                $input7['posisi'] = 'D';
                $input7['debet'] = $returJual->pendapatan_barang;
                $input7['kredit'] = 0;
                $input7['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input7);
            }
        }
    }


    public function deleted(ReturJual $returJual)
    {
        AccJurnal::where('invoice', $returJual->invoice)->delete();
    }


    public function restored(ReturJual $returJual)
    {
        //
    }


    public function forceDeleted(ReturJual $returJual)
    {
        //
    }
}
