<?php

namespace App\Observers;

use App\Models\ReturBeli;
use App\Models\AccJurnal;
//use App\Models\Suplier;

class ReturBeliObserver
{
    public function created(ReturBeli $returBeli)
    {
        $tipe = $returBeli->tipe;
        $invoice = $returBeli->invoice;
        $tanggal = $returBeli->tanggal;
        $keterangan = $returBeli->invoice;
        //kas > 0
        if ($returBeli->kas > 0) {
            $input1['tipe'] = $tipe; //no edit
            $input1['induk'] = 1;
            $input1['invoice'] = $invoice; //no edit
            $input1['tanggal'] = $tanggal; //no edit
            $input1['kode_acc'] = $returBeli->kode_kas;
            $input1['kode_lawan'] = '1-1301';
            $input1['jumlah'] = $returBeli->kas;
            $input1['posisi'] = 'D';
            $input1['debet'] = $returBeli->kas;
            $input1['kredit'] = 0;
            $input1['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input1);
        }
        // bank > 0
        if ($returBeli->bank > 0) {
            $input2['tipe'] = $tipe; //no edit
            $input2['induk'] = 1;
            $input2['invoice'] = $invoice; //no edit
            $input2['tanggal'] = $tanggal; //no edit
            $input2['kode_acc'] = $returBeli->kode_bank;
            $input2['kode_lawan'] = '1-1301';
            $input2['jumlah'] = $returBeli->bank;
            $input2['posisi'] = 'D';
            $input2['debet'] = $returBeli->bank;
            $input2['kredit'] = 0;
            $input2['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input2);
        }
        // hutang > 0
        if ($returBeli->hutang > 0) {
            $input3['tipe'] = $tipe; //no edit
            $input3['induk'] = 1;
            $input3['invoice'] = $invoice; //no edit
            $input3['tanggal'] = $tanggal; //no edit
            $input3['kode_acc'] = $returBeli->kode_hutang;
            $input3['kode_lawan'] = '1-1301';
            $input3['jumlah'] = $returBeli->hutang;
            $input3['posisi'] = 'D';
            $input3['debet'] = $returBeli->hutang;
            $input3['kredit'] = 0;
            $input3['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input3);
            // $Suplier = Suplier::findorfail($returBeli->suplier_id);
            // if ($Suplier) {
            //     $sisa_hutang = $Suplier->hutang - $returBeli->hutang;
            //     $Suplier->update(['hutang' => $sisa_hutang]);
            // }
        }
        // ppn > 0
        if ($returBeli->ppn > 0) {
            $input4['tipe'] = $tipe; //no edit
            $input4['induk'] = 0;
            $input4['invoice'] = $invoice; //no edit
            $input4['tanggal'] = $tanggal; //no edit
            $input4['kode_acc'] = '1-1410';
            $input4['kode_lawan'] = '1-1301';
            $input4['jumlah'] = $returBeli->ppn;
            $input4['posisi'] = 'K';
            $input4['debet'] = 0;
            $input4['kredit'] = $returBeli->ppn;
            $input4['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input4);
        }
        if ($returBeli->persediaan > 0) {
            $input5['tipe'] = $tipe; //no edit
            $input5['induk'] = 0;
            $input5['invoice'] = $invoice; //no edit
            $input5['tanggal'] = $tanggal; //no edit
            $input5['kode_acc'] = '1-1301';
            $input5['kode_lawan'] = $returBeli->kode_kas;
            $input5['jumlah'] = $returBeli->persediaan;
            $input5['posisi'] = 'K';
            $input5['debet'] = 0;
            $input5['kredit'] = $returBeli->persediaan;
            $input5['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input5);
        }
    }

    public function updated(ReturBeli $returBeli)
    {
        $tipe = $returBeli->tipe;
        $invoice = $returBeli->invoice;
        $tanggal = $returBeli->tanggal;
        $keterangan = $returBeli->invoice;
        //kas > 0
        if ($returBeli->kas != $returBeli->getOriginal('kas')) {
            AccJurnal::where('invoice', $returBeli->invoice)
                ->where('kode_acc', $returBeli->getOriginal('kode_kas'))
                ->delete();
            if ($returBeli->kas > 0) {
                $input1['tipe'] = $tipe; //no edit
                $input1['induk'] = 1;
                $input1['invoice'] = $invoice; //no edit
                $input1['tanggal'] = $tanggal; //no edit
                $input1['kode_acc'] = $returBeli->kode_kas;
                $input1['kode_lawan'] = '1-1301';
                $input1['jumlah'] = $returBeli->kas;
                $input1['posisi'] = 'D';
                $input1['debet'] = $returBeli->kas;
                $input1['kredit'] = 0;
                $input1['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input1);
            }
        }
        // bank > 0
        if ($returBeli->bank != $returBeli->getOriginal('bank')) {
            AccJurnal::where('invoice', $returBeli->invoice)
                ->where('kode_acc', $returBeli->getOriginal('kode_bank'))
                ->delete();
            if ($returBeli->bank > 0) {
                $input2['tipe'] = $tipe; //no edit
                $input2['induk'] = 1;
                $input2['invoice'] = $invoice; //no edit
                $input2['tanggal'] = $tanggal; //no edit
                $input2['kode_acc'] = $returBeli->kode_bank;
                $input2['kode_lawan'] = '1-1301';
                $input2['jumlah'] = $returBeli->bank;
                $input2['posisi'] = 'D';
                $input2['debet'] = $returBeli->bank;
                $input2['kredit'] = 0;
                $input2['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input2);
            }
        }
        // hutang > 0
        if ($returBeli->hutang != $returBeli->getOriginal('hutang')) {
            AccJurnal::where('invoice', $returBeli->invoice)
                ->where('kode_acc', $returBeli->getOriginal('kode_hutang'))
                ->delete();
            if ($returBeli->hutang > 0) {
                $input3['tipe'] = $tipe; //no edit
                $input3['induk'] = 1;
                $input3['invoice'] = $invoice; //no edit
                $input3['tanggal'] = $tanggal; //no edit
                $input3['kode_acc'] = $returBeli->kode_hutang;
                $input3['kode_lawan'] = '1-1301';
                $input3['jumlah'] = $returBeli->hutang;
                $input3['posisi'] = 'D';
                $input3['debet'] = $returBeli->hutang;
                $input3['kredit'] = 0;
                $input3['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input3);
            }
        }
        // ppn > 0
        if ($returBeli->ppn != $returBeli->getOriginal('ppn')) {
            AccJurnal::where('invoice', $returBeli->invoice)
                ->where('kode_acc', '1-1410')
                ->delete();
            if ($returBeli->ppn > 0) {
                $input4['tipe'] = $tipe; //no edit
                $input4['induk'] = 0;
                $input4['invoice'] = $invoice; //no edit
                $input4['tanggal'] = $tanggal; //no edit
                $input4['kode_acc'] = '1-1410';
                $input4['kode_lawan'] = '1-1301';
                $input4['jumlah'] = $returBeli->ppn;
                $input4['posisi'] = 'K';
                $input4['debet'] = 0;
                $input4['kredit'] = $returBeli->ppn;
                $input4['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input4);
            }
        }
        if ($returBeli->persediaan != $returBeli->getOriginal('persediaan')) {
            AccJurnal::where('invoice', $returBeli->invoice)
                ->where('kode_acc', '1-1301')
                ->delete();
            if ($returBeli->persediaan > 0) {
                $input5['tipe'] = $tipe; //no edit
                $input5['induk'] = 0;
                $input5['invoice'] = $invoice; //no edit
                $input5['tanggal'] = $tanggal; //no edit
                $input5['kode_acc'] = '1-1301';
                $input5['kode_lawan'] = $returBeli->kode_kas;
                $input5['jumlah'] = $returBeli->persediaan;
                $input5['posisi'] = 'K';
                $input5['debet'] = 0;
                $input5['kredit'] = $returBeli->persediaan;
                $input5['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input5);
            }
        }
        //$Suplierold = Suplier::findorfail($returBeli->getOriginal('suplier_id'));
        // if ($Suplierold) {
        //     $sisa_hutangold = ($Suplierold->hutang - $returBeli->getOriginal('hutang'));
        //     $Suplierold->update(['hutang' => $sisa_hutangold]);
        // }
        // $Supliernew = Suplier::findorfail($returBeli->suplier_id);
        // if ($Supliernew) {
        //     $sisa_hutang = $Supliernew->hutang + $returBeli->hutang;
        //     $Supliernew->update(['hutang' => $sisa_hutang]);
        // }
    }

    public function deleted(ReturBeli $returBeli)
    {
        AccJurnal::where('invoice', $returBeli->invoice)->delete();
        // $Suplier = Suplier::findorfail($returBeli->suplier_id);
        // if ($Suplier) {
        //     $sisa_hutang = $Suplier->hutang - $returBeli->hutang;
        //     $Suplier->update(['hutang' => $sisa_hutang]);
        // }
    }
    public function restored(ReturBeli $returBeli)
    {
        //
    }

    public function forceDeleted(ReturBeli $returBeli)
    {
        //
    }
}
