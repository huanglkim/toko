<?php

namespace App\Observers;

use App\Models\TransaksiOut;
use App\Models\AccJurnal;

class TransaksiOutObserver
{

    public function created(TransaksiOut $transaksiOut)
    {
        $tipe = $transaksiOut->tipe;
        $invoice = $transaksiOut->invoice;
        $tanggal = $transaksiOut->tanggal;
        $keterangan = $transaksiOut->invoice;
        //kas > 0
        if ($tanggal != null) { //jika ada tanggal
            // potongan > 0
            if ($transaksiOut->pot > 0) {
                $input9['tipe'] = $tipe; //no edit
                $input9['induk'] = 0;
                $input9['invoice'] = $invoice; //no edit
                $input9['tanggal'] = $tanggal; //no edit
                $input9['kode_acc'] = $transaksiOut->kode_pot;
                $input9['kode_lawan'] = '1-1301';
                $input9['jumlah'] = $transaksiOut->pot;
                $input9['posisi'] = 'D';
                $input9['debet'] = $transaksiOut->pot;
                $input9['kredit'] = 0;
                $input9['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input9);
            }
            //kas > 0
            if ($transaksiOut->kas > 0) {
                $input1['tipe'] = $tipe; //no edit
                $input1['induk'] = 1;
                $input1['invoice'] = $invoice; //no edit
                $input1['tanggal'] = $tanggal; //no edit
                $input1['kode_acc'] = $transaksiOut->kode_kas;
                $input1['kode_lawan'] = '1-1301';
                $input1['jumlah'] = $transaksiOut->kas;
                $input1['posisi'] = 'D';
                $input1['debet'] = $transaksiOut->kas;
                $input1['kredit'] = 0;
                $input1['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input1);
            }
            // bank > 0
            if ($transaksiOut->bank > 0) {
                $input2['tipe'] = $tipe; //no edit
                $input2['induk'] = 1;
                $input2['invoice'] = $invoice; //no edit
                $input2['tanggal'] = $tanggal; //no edit
                $input2['kode_acc'] = $transaksiOut->kode_bank;
                $input2['kode_lawan'] = '1-1301';
                $input2['jumlah'] = $transaksiOut->bank;
                $input2['posisi'] = 'D';
                $input2['debet'] = $transaksiOut->bank;
                $input2['kredit'] = 0;
                $input2['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input2);
            }
            // piutang > 0
            if ($transaksiOut->piutang > 0) {
                $input3['tipe'] = $tipe; //no edit
                $input3['induk'] = 1;
                $input3['invoice'] = $invoice; //no edit
                $input3['tanggal'] = $tanggal; //no edit
                $input3['kode_acc'] = $transaksiOut->kode_piutang;
                $input3['kode_lawan'] = '1-1301';
                $input3['jumlah'] = $transaksiOut->piutang;
                $input3['posisi'] = 'D';
                $input3['debet'] = $transaksiOut->piutang;
                $input3['kredit'] = 0;
                $input3['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input3);
            }
            // ppn > 0
            if ($transaksiOut->ppn > 0) {
                $input4['tipe'] = $tipe; //no edit
                $input4['induk'] = 0;
                $input4['invoice'] = $invoice; //no edit
                $input4['tanggal'] = $tanggal; //no edit
                $input4['kode_acc'] = '2-4110';
                $input4['kode_lawan'] = '1-1301';
                $input4['jumlah'] = $transaksiOut->ppn;
                $input4['posisi'] = 'K';
                $input4['debet'] = 0;
                $input4['kredit'] = $transaksiOut->ppn;
                $input4['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input4);
            }
            //total_hpp //
            if ($transaksiOut->total_hpp > 0) {
                $input5['tipe'] = $tipe; //no edit
                $input5['induk'] = 0;
                $input5['invoice'] = $invoice; //no edit
                $input5['tanggal'] = $tanggal; //no edit
                $input5['kode_acc'] = '1-1301';
                $input5['kode_lawan'] = '5-1100';
                $input5['jumlah'] = $transaksiOut->total_hpp;
                $input5['posisi'] = 'K';
                $input5['debet'] = 0;
                $input5['kredit'] = $transaksiOut->total_hpp;
                $input5['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input5);

                $input6['tipe'] = $tipe; //no edit
                $input6['induk'] = 0;
                $input6['invoice'] = $invoice; //no edit
                $input6['tanggal'] = $tanggal; //no edit
                $input6['kode_acc'] = '5-1100';
                $input6['kode_lawan'] = '1-1301';
                $input6['jumlah'] = $transaksiOut->total_hpp;
                $input6['posisi'] = 'D';
                $input6['debet'] = $transaksiOut->total_hpp;
                $input6['kredit'] = 0;
                $input6['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input6);
            }
            // pendapatan_barang
            if ($transaksiOut->pendapatan_barang > 0) {
                $input7['tipe'] = $tipe; //no edit
                $input7['induk'] = 0;
                $input7['invoice'] = $invoice; //no edit
                $input7['tanggal'] = $tanggal; //no edit
                $input7['kode_acc'] = '4-1100';
                $input7['kode_lawan'] = $transaksiOut->kode_kas;
                $input7['jumlah'] = $transaksiOut->pendapatan_barang;
                $input7['posisi'] = 'K';
                $input7['debet'] = 0;
                $input7['kredit'] = $transaksiOut->pendapatan_barang;
                $input7['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input7);
            }
            //pendapatan_jasa
            if ($transaksiOut->pendapatan_jasa > 0) {
                $input8['tipe'] = $tipe; //no edit
                $input8['induk'] = 0;
                $input8['invoice'] = $invoice; //no edit
                $input8['tanggal'] = $tanggal; //no edit
                $input8['kode_acc'] = '4-1200';
                $input8['kode_lawan'] = $transaksiOut->kode_kas;
                $input8['jumlah'] = $transaksiOut->pendapatan_jasa;
                $input8['posisi'] = 'K';
                $input8['debet'] = 0;
                $input8['kredit'] = $transaksiOut->pendapatan_jasa;
                $input8['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input8);
            }
        }
    }

    public function updated(TransaksiOut $transaksiOut)
    {
        $tipe = $transaksiOut->tipe;
        $invoice = $transaksiOut->invoice;
        $tanggal = $transaksiOut->tanggal;
        $keterangan = $transaksiOut->invoice;
        //kas > 0
        if ($tanggal != null) { //jika ada tanggal
            //pot
            AccJurnal::where('invoice', $transaksiOut->invoice)
                ->where('kode_acc', $transaksiOut->getOriginal('kode_pot'))
                ->delete();
            if ($transaksiOut->pot > 0) {
                $input9['tipe'] = $tipe; //no edit
                $input9['induk'] = 0;
                $input9['invoice'] = $invoice; //no edit
                $input9['tanggal'] = $tanggal; //no edit
                $input9['kode_acc'] = $transaksiOut->kode_pot;
                $input9['kode_lawan'] = '1-1301';
                $input9['jumlah'] = $transaksiOut->pot;
                $input9['posisi'] = 'D';
                $input9['debet'] = $transaksiOut->pot;
                $input9['kredit'] = 0;
                $input9['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input9);
            }

            //kas
            if ($transaksiOut->kas != $transaksiOut->getOriginal('kas')) {
                AccJurnal::where('invoice', $transaksiOut->invoice)
                    ->where('kode_acc', $transaksiOut->getOriginal('kode_kas'))
                    ->delete();
                if ($transaksiOut->kas > 0) {
                    $input1['tipe'] = $tipe; //no edit
                    $input1['induk'] = 1;
                    $input1['invoice'] = $invoice; //no edit
                    $input1['tanggal'] = $tanggal; //no edit
                    $input1['kode_acc'] = $transaksiOut->kode_kas;
                    $input1['kode_lawan'] = '1-1301';
                    $input1['jumlah'] = $transaksiOut->kas;
                    $input1['posisi'] = 'D';
                    $input1['debet'] = $transaksiOut->kas;
                    $input1['kredit'] = 0;
                    $input1['keterangan'] = $keterangan; //no edit
                    AccJurnal::create($input1);
                }
            }
            // bank > 0
            if ($transaksiOut->bank != $transaksiOut->getOriginal('bank')) {
                AccJurnal::where('invoice', $transaksiOut->invoice)
                    ->where('kode_acc', $transaksiOut->getOriginal('kode_bank'))
                    ->delete();
                if ($transaksiOut->bank > 0) {
                    $input2['tipe'] = $tipe; //no edit
                    $input2['induk'] = $transaksiOut->kas > 0 ? 0 : 1;
                    $input2['invoice'] = $invoice; //no edit
                    $input2['tanggal'] = $tanggal; //no edit
                    $input2['kode_acc'] = $transaksiOut->kode_bank;
                    $input2['kode_lawan'] = '1-1301';
                    $input2['jumlah'] = $transaksiOut->bank;
                    $input2['posisi'] = 'D';
                    $input2['debet'] = $transaksiOut->bank;
                    $input2['kredit'] = 0;
                    $input2['keterangan'] = $keterangan; //no edit
                    AccJurnal::create($input2);
                }
            }
            // piutang > 0
            if ($transaksiOut->piutang != $transaksiOut->getOriginal('piutang')) {
                AccJurnal::where('invoice', $transaksiOut->invoice)
                    ->where('kode_acc', $transaksiOut->getOriginal('kode_piutang'))
                    ->delete();
                if ($transaksiOut->piutang > 0) {
                    $input3['tipe'] = $tipe; //no edit
                    $input2['induk'] = $transaksiOut->kas > 0 ? 0 : 1;
                    $input3['invoice'] = $invoice; //no edit
                    $input3['tanggal'] = $tanggal; //no edit
                    $input3['kode_acc'] = $transaksiOut->kode_piutang;
                    $input3['kode_lawan'] = '1-1301';
                    $input3['jumlah'] = $transaksiOut->piutang;
                    $input3['posisi'] = 'D';
                    $input3['debet'] = $transaksiOut->piutang;
                    $input3['kredit'] = 0;
                    $input3['keterangan'] = $keterangan; //no edit
                    AccJurnal::create($input3);
                }
            }
            // ppn > 0
            if ($transaksiOut->ppn != $transaksiOut->getOriginal('ppn')) {
                AccJurnal::where('invoice', $transaksiOut->invoice)
                    ->where('kode_acc', '2-4110')
                    ->delete();
                if ($transaksiOut->ppn > 0) {
                    $input4['tipe'] = $tipe; //no edit
                    $input4['induk'] = 0;
                    $input4['invoice'] = $invoice; //no edit
                    $input4['tanggal'] = $tanggal; //no edit
                    $input4['kode_acc'] = '2-4110';
                    $input4['kode_lawan'] = '1-1301';
                    $input4['jumlah'] = $transaksiOut->ppn;
                    $input4['posisi'] = 'K';
                    $input4['debet'] = 0;
                    $input4['kredit'] = $transaksiOut->ppn;
                    $input4['keterangan'] = $keterangan; //no edit
                    AccJurnal::create($input4);
                }
            }
            //total_hpp //
            if ($transaksiOut->total_hpp != $transaksiOut->getOriginal('total_hpp')) {
                AccJurnal::where('invoice', $transaksiOut->invoice)
                    ->whereIn('kode_acc', ['1-1301', '5-1100'])
                    ->delete();
                if ($transaksiOut->total_hpp > 0) {
                    $input5['tipe'] = $tipe; //no edit
                    $input5['induk'] = 0;
                    $input5['invoice'] = $invoice; //no edit
                    $input5['tanggal'] = $tanggal; //no edit
                    $input5['kode_acc'] = '1-1301';
                    $input5['kode_lawan'] = '5-1100';
                    $input5['jumlah'] = $transaksiOut->total_hpp;
                    $input5['posisi'] = 'K';
                    $input5['debet'] = 0;
                    $input5['kredit'] = $transaksiOut->total_hpp;
                    $input5['keterangan'] = $keterangan; //no edit
                    AccJurnal::create($input5);

                    $input6['tipe'] = $tipe; //no edit
                    $input6['induk'] = 0;
                    $input6['invoice'] = $invoice; //no edit
                    $input6['tanggal'] = $tanggal; //no edit
                    $input6['kode_acc'] = '5-1100';
                    $input6['kode_lawan'] = '1-1301';
                    $input6['jumlah'] = $transaksiOut->total_hpp;
                    $input6['posisi'] = 'D';
                    $input6['debet'] = $transaksiOut->total_hpp;
                    $input6['kredit'] = 0;
                    $input6['keterangan'] = $keterangan; //no edit
                    AccJurnal::create($input6);
                }
            }
            // pendapatan_barang
            if ($transaksiOut->pendapatan_barang != $transaksiOut->getOriginal('pendapatan_barang')) {
                AccJurnal::where('invoice', $transaksiOut->invoice)
                    ->where('kode_acc', '4-1100')
                    ->delete();
                if ($transaksiOut->pendapatan_barang > 0) {
                    $input7['tipe'] = $tipe; //no edit
                    $input7['induk'] = 0;
                    $input7['invoice'] = $invoice; //no edit
                    $input7['tanggal'] = $tanggal; //no edit
                    $input7['kode_acc'] = '4-1100';
                    $input7['kode_lawan'] = $transaksiOut->kode_kas;
                    $input7['jumlah'] = $transaksiOut->pendapatan_barang;
                    $input7['posisi'] = 'K';
                    $input7['debet'] = 0;
                    $input7['kredit'] = $transaksiOut->pendapatan_barang;
                    $input7['keterangan'] = $keterangan; //no edit
                    AccJurnal::create($input7);
                }
            }
            //pendapatan_jasa
            if ($transaksiOut->pendapatan_jasa != $transaksiOut->getOriginal('pendapatan_jasa')) {
                AccJurnal::where('invoice', $transaksiOut->invoice)
                    ->where('kode_acc', '4-1200')
                    ->delete();
                if ($transaksiOut->pendapatan_jasa > 0) {
                    $input8['tipe'] = $tipe; //no edit
                    $input8['induk'] = 0;
                    $input8['invoice'] = $invoice; //no edit
                    $input8['tanggal'] = $tanggal; //no edit
                    $input8['kode_acc'] = '4-1200';
                    $input8['kode_lawan'] = $transaksiOut->kode_kas;
                    $input8['jumlah'] = $transaksiOut->pendapatan_jasa;
                    $input8['posisi'] = 'K';
                    $input8['debet'] = 0;
                    $input8['kredit'] = $transaksiOut->pendapatan_jasa;
                    $input8['keterangan'] = $keterangan; //no edit
                    AccJurnal::create($input8);
                }
            }
        }
    }

    public function deleted(TransaksiOut $transaksiOut)
    {
        AccJurnal::where('invoice', $transaksiOut->invoice)->delete();
    }

    public function restored(TransaksiOut $transaksiOut)
    {
        //
    }

    public function forceDeleted(TransaksiOut $transaksiOut)
    {
        //
    }
}
