<?php

namespace App\Observers;

use App\Models\TransaksiIn;
use App\Models\AccJurnal;
use App\Models\Suplier;

class TransaksiInObserver
{

    public function created(TransaksiIn $transaksiIn)
    {
        $tipe = $transaksiIn->tipe;
        $invoice = $transaksiIn->invoice;
        $tanggal = $transaksiIn->tanggal;
        $keterangan = $transaksiIn->invoice;
        //kas > 0
        if ($transaksiIn->kas > 0) {
            $input1['tipe'] = $tipe; //no edit
            $input1['induk'] = 1;
            $input1['invoice'] = $invoice; //no edit
            $input1['tanggal'] = $tanggal; //no edit
            $input1['kode_acc'] = $transaksiIn->kode_kas;
            $input1['kode_lawan'] = '1-1301';
            $input1['jumlah'] = $transaksiIn->kas;
            $input1['posisi'] = 'K';
            $input1['debet'] = 0;
            $input1['kredit'] = $transaksiIn->kas;
            $input1['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input1);
        }
        // bank > 0
        if ($transaksiIn->bank > 0) {
            $input2['tipe'] = $tipe; //no edit
            $input2['induk'] = 1;
            $input2['invoice'] = $invoice; //no edit
            $input2['tanggal'] = $tanggal; //no edit
            $input2['kode_acc'] = $transaksiIn->kode_bank;
            $input2['kode_lawan'] = '1-1301';
            $input2['jumlah'] = $transaksiIn->bank;
            $input2['posisi'] = 'K';
            $input2['debet'] = 0;
            $input2['kredit'] = $transaksiIn->bank;
            $input2['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input2);
        }
        // hutang > 0
        if ($transaksiIn->hutang > 0) {
            $input3['tipe'] = $tipe; //no edit
            $input3['induk'] = 1;
            $input3['invoice'] = $invoice; //no edit
            $input3['tanggal'] = $tanggal; //no edit
            $input3['kode_acc'] = $transaksiIn->kode_hutang;
            $input3['kode_lawan'] = '1-1301';
            $input3['jumlah'] = $transaksiIn->hutang;
            $input3['posisi'] = 'K';
            $input3['debet'] = 0;
            $input3['kredit'] = $transaksiIn->hutang;
            $input3['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input3);
            $Suplier = Suplier::findorfail($transaksiIn->suplier_id);
            if ($Suplier) {
                $sisa_hutang = $Suplier->hutang + $transaksiIn->hutang;
                $Suplier->update(['hutang' => $sisa_hutang]);
            }
        }
        // ppn > 0
        if ($transaksiIn->ppn > 0) {
            $input4['tipe'] = $tipe; //no edit
            $input4['induk'] = 0;
            $input4['invoice'] = $invoice; //no edit
            $input4['tanggal'] = $tanggal; //no edit
            $input4['kode_acc'] = '1-1410';
            $input4['kode_lawan'] = '1-1301';
            $input4['jumlah'] = $transaksiIn->ppn;
            $input4['posisi'] = 'D';
            $input4['debet'] = $transaksiIn->ppn;
            $input4['kredit'] = 0;
            $input4['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input4);
        }
        if ($transaksiIn->persediaan > 0) {
            $input5['tipe'] = $tipe; //no edit
            $input5['induk'] = 0;
            $input5['invoice'] = $invoice; //no edit
            $input5['tanggal'] = $tanggal; //no edit
            $input5['kode_acc'] = '1-1301';
            $input5['kode_lawan'] = $transaksiIn->kode_kas;
            $input5['jumlah'] = $transaksiIn->persediaan;
            $input5['posisi'] = 'D';
            $input5['debet'] = $transaksiIn->persediaan;
            $input5['kredit'] = 0;
            $input5['keterangan'] = $keterangan; //no edit
            AccJurnal::create($input5);
        }
    }

    public function updated(TransaksiIn $transaksiIn)
    {
        $tipe = $transaksiIn->tipe;
        $invoice = $transaksiIn->invoice;
        $tanggal = $transaksiIn->tanggal;
        $keterangan = $transaksiIn->invoice;
        //kas > 0
        if ($transaksiIn->kas != $transaksiIn->getOriginal('kas')) {
            AccJurnal::where('invoice', $transaksiIn->invoice)
                ->where('kode_acc', $transaksiIn->getOriginal('kode_kas'))
                ->delete();
            if ($transaksiIn->kas > 0) {
                $input1['tipe'] = $tipe; //no edit
                $input1['induk'] = 1;
                $input1['invoice'] = $invoice; //no edit
                $input1['tanggal'] = $tanggal; //no edit
                $input1['kode_acc'] = $transaksiIn->kode_kas;
                $input1['kode_lawan'] = '1-1301';
                $input1['jumlah'] = $transaksiIn->kas;
                $input1['posisi'] = 'K';
                $input1['debet'] = 0;
                $input1['kredit'] = $transaksiIn->kas;
                $input1['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input1);
            }
        }
        // bank > 0
        if ($transaksiIn->bank != $transaksiIn->getOriginal('bank')) {
            AccJurnal::where('invoice', $transaksiIn->invoice)
                ->where('kode_acc', $transaksiIn->getOriginal('kode_bank'))
                ->delete();
            if ($transaksiIn->bank > 0) {
                $input2['tipe'] = $tipe; //no edit
                $input2['induk'] = 1;
                $input2['invoice'] = $invoice; //no edit
                $input2['tanggal'] = $tanggal; //no edit
                $input2['kode_acc'] = $transaksiIn->kode_bank;
                $input2['kode_lawan'] = '1-1301';
                $input2['jumlah'] = $transaksiIn->bank;
                $input2['posisi'] = 'K';
                $input2['debet'] = 0;
                $input2['kredit'] = $transaksiIn->bank;
                $input2['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input2);
            }
        }
        // hutang > 0
        if ($transaksiIn->hutang != $transaksiIn->getOriginal('hutang')) {
            AccJurnal::where('invoice', $transaksiIn->invoice)
                ->where('kode_acc', $transaksiIn->getOriginal('kode_hutang'))
                ->delete();
            if ($transaksiIn->hutang > 0) {
                $input3['tipe'] = $tipe; //no edit
                $input3['induk'] = 1;
                $input3['invoice'] = $invoice; //no edit
                $input3['tanggal'] = $tanggal; //no edit
                $input3['kode_acc'] = $transaksiIn->kode_hutang;
                $input3['kode_lawan'] = '1-1301';
                $input3['jumlah'] = $transaksiIn->hutang;
                $input3['posisi'] = 'K';
                $input3['debet'] = 0;
                $input3['kredit'] = $transaksiIn->hutang;
                $input3['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input3);
            }
        }
        // ppn > 0
        if ($transaksiIn->ppn != $transaksiIn->getOriginal('ppn')) {
            AccJurnal::where('invoice', $transaksiIn->invoice)
                ->where('kode_acc', '1-1410')
                ->delete();
            if ($transaksiIn->ppn > 0) {
                $input4['tipe'] = $tipe; //no edit
                $input4['induk'] = 0;
                $input4['invoice'] = $invoice; //no edit
                $input4['tanggal'] = $tanggal; //no edit
                $input4['kode_acc'] = '1-1410';
                $input4['kode_lawan'] = '1-1301';
                $input4['jumlah'] = $transaksiIn->ppn;
                $input4['posisi'] = 'D';
                $input4['debet'] = $transaksiIn->ppn;
                $input4['kredit'] = 0;
                $input4['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input4);
            }
        }
        if ($transaksiIn->persediaan != $transaksiIn->getOriginal('persediaan')) {
            AccJurnal::where('invoice', $transaksiIn->invoice)
                ->where('kode_acc', '1-1301')
                ->delete();
            if ($transaksiIn->persediaan > 0) {
                $input5['tipe'] = $tipe; //no edit
                $input5['induk'] = 0;
                $input5['invoice'] = $invoice; //no edit
                $input5['tanggal'] = $tanggal; //no edit
                $input5['kode_acc'] = '1-1301';
                $input5['kode_lawan'] = $transaksiIn->kode_kas;
                $input5['jumlah'] = $transaksiIn->persediaan;
                $input5['posisi'] = 'D';
                $input5['debet'] = $transaksiIn->persediaan;
                $input5['kredit'] = 0;
                $input5['keterangan'] = $keterangan; //no edit
                AccJurnal::create($input5);
            }
        }
        $Suplierold = Suplier::findorfail($transaksiIn->getOriginal('suplier_id'));
        if ($Suplierold) {
            $sisa_hutangold = ($Suplierold->hutang - $transaksiIn->getOriginal('hutang'));
            $Suplierold->update(['hutang' => $sisa_hutangold]);
        }
        $Supliernew = Suplier::findorfail($transaksiIn->suplier_id);
        if ($Supliernew) {
            $sisa_hutang = $Supliernew->hutang + $transaksiIn->hutang;
            $Supliernew->update(['hutang' => $sisa_hutang]);
        }
    }

    public function deleted(TransaksiIn $transaksiIn)
    {
        AccJurnal::where('invoice', $transaksiIn->invoice)->delete();
        $Suplier = Suplier::findorfail($transaksiIn->suplier_id);
        if ($Suplier) {
            $sisa_hutang = $Suplier->hutang - $transaksiIn->hutang;
            $Suplier->update(['hutang' => $sisa_hutang]);
        }
    }

    public function restored(TransaksiIn $transaksiIn)
    {
        //
    }

    public function forceDeleted(TransaksiIn $transaksiIn)
    {
        //
    }
}
