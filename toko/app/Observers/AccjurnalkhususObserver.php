<?php

namespace App\Observers;

use App\Models\Accjurnalkhusus;
use App\Models\Accjurnal;
use App\Models\Users;

class AccjurnalkhususObserver
{
    public function created(Accjurnalkhusus $accjurnalkhusus)
    {
        $tipe = $accjurnalkhusus->tipe;
        $invoice = $accjurnalkhusus->invoice;
        $tanggal = $accjurnalkhusus->tanggal;
        $posisi = $accjurnalkhusus->posisi;
        $jumlah = $accjurnalkhusus->jumlah;
        $keterangan = $accjurnalkhusus->keterangan;
        //kas > 0
        if ($accjurnalkhusus->jumlah > 0) {
            $input1['tipe'] = $tipe; //no edit
            $input1['induk'] = 1;
            $input1['invoice'] = $invoice; //no edit
            $input1['tanggal'] = $tanggal; //no edit
            $input1['kode_acc'] = $accjurnalkhusus->kode_acc;
            $input1['kode_lawan'] = $accjurnalkhusus->kode_lawan;
            $input1['jumlah'] = $accjurnalkhusus->jumlah;
            if ($posisi == 'D') {
                $input1['posisi'] = 'D'; //no edit
                $input1['debet'] = $jumlah;
                $input1['kredit'] = 0;
            } else {
                $input1['posisi'] = 'K'; //no edit
                $input1['debet'] = 0;
                $input1['kredit'] = $jumlah;
            }
            $input1['keterangan'] = $keterangan; //no edit
            Accjurnal::create($input1);

            $input0['tipe'] = $tipe; //no edit
            $input0['induk'] = 0;
            $input0['invoice'] = $invoice; //no edit
            $input0['tanggal'] = $tanggal; //no edit
            $input0['kode_acc'] = $accjurnalkhusus->kode_lawan;
            $input0['kode_lawan'] = $accjurnalkhusus->kode_acc;
            $input0['jumlah'] = $accjurnalkhusus->jumlah;
            if ($posisi == 'D') {
                $input0['posisi'] = 'K'; //no edit
                $input0['debet'] = 0;
                $input0['kredit'] = $jumlah;
            } else {
                $input0['posisi'] = 'D'; //no edit
                $input0['debet'] = $jumlah;
                $input0['kredit'] = 0;
            }
            $input0['keterangan'] = $keterangan; //no edit
            Accjurnal::create($input0);
        }

        //piutang karyawan
        $karyawan_id = $accjurnalkhusus->karyawan_id;
        if ($tipe == 'PK' && $karyawan_id != null) {
            $karyawan = Users::find($karyawan_id);
            if ($posisi == 'K') { //kas kredit 
                $totalpiutang = $karyawan->piutang + $jumlah;
            } else {
                $totalpiutang = $karyawan->piutang - $jumlah;
            }
            $karyawan->update(['piutang' => $totalpiutang]);
        }
    }

    public function updated(Accjurnalkhusus $accjurnalkhusus)
    {
        $tipe = $accjurnalkhusus->tipe;
        $invoice = $accjurnalkhusus->invoice;
        $tanggal = $accjurnalkhusus->tanggal;
        $posisi = $accjurnalkhusus->posisi;
        $jumlah = $accjurnalkhusus->jumlah;
        $keterangan = $accjurnalkhusus->keterangan;
        //kas > 0
        Accjurnal::where('invoice', $accjurnalkhusus->invoice)->delete();
        if ($accjurnalkhusus->jumlah > 0) {
            $input1['tipe'] = $tipe; //no edit
            $input1['induk'] = 1;
            $input1['invoice'] = $invoice; //no edit
            $input1['tanggal'] = $tanggal; //no edit
            $input1['kode_acc'] = $accjurnalkhusus->kode_acc;
            $input1['kode_lawan'] = $accjurnalkhusus->kode_lawan;
            $input1['jumlah'] = $accjurnalkhusus->jumlah;
            if ($posisi == 'D') {
                $input1['posisi'] = 'D'; //no edit
                $input1['debet'] = $jumlah;
                $input1['kredit'] = 0;
            } else {
                $input1['posisi'] = 'K'; //no edit
                $input1['debet'] = 0;
                $input1['kredit'] = $jumlah;
            }
            $input1['keterangan'] = $keterangan; //no edit
            Accjurnal::create($input1);

            $input0['tipe'] = $tipe; //no edit
            $input0['induk'] = 0;
            $input0['invoice'] = $invoice; //no edit
            $input0['tanggal'] = $tanggal; //no edit
            $input0['kode_acc'] = $accjurnalkhusus->kode_lawan;
            $input0['kode_lawan'] = $accjurnalkhusus->kode_acc;
            $input0['jumlah'] = $accjurnalkhusus->jumlah;
            if ($posisi == 'D') {
                $input0['posisi'] = 'K'; //no edit
                $input0['debet'] = 0;
                $input0['kredit'] = $jumlah;
            } else {
                $input0['posisi'] = 'D'; //no edit
                $input0['debet'] = $jumlah;
                $input0['kredit'] = 0;
            }
            $input0['keterangan'] = $keterangan; //no edit
            Accjurnal::create($input0);
        }

        //piutang karyawan
        $karyawan_id = $accjurnalkhusus->karyawan_id;
        if ($tipe == 'PK' && $karyawan_id != null) {
            $karyawan = Users::find($karyawan_id);
            if ($posisi == 'K') { //kas kredit piutang debet piutang bertambah
                $totalpiutang = $karyawan->piutang - $accjurnalkhusus->getOriginal('jumlah') + $accjurnalkhusus->jumlah;
            } else {
                $totalpiutang = $karyawan->piutang + $accjurnalkhusus->getOriginal('jumlah') - $accjurnalkhusus->jumlah;
            }
            $karyawan->update(['piutang' => $totalpiutang]);
        }
    }

    public function deleted(Accjurnalkhusus $accjurnalkhusus)
    {
        Accjurnal::where('invoice', $accjurnalkhusus->invoice)->delete();
        //piutang karyawan
        $tipe = $accjurnalkhusus->tipe;
        $posisi = $accjurnalkhusus->posisi;
        $jumlah = $accjurnalkhusus->jumlah;

        $karyawan_id = $accjurnalkhusus->karyawan_id;
        if ($tipe == 'PK' && $karyawan_id != null) {
            $karyawan = Users::find($karyawan_id);
            if ($posisi == 'K') { //kas kredit 
                $totalpiutang = $karyawan->piutang - $jumlah;
            } else {
                $totalpiutang = $karyawan->piutang + $jumlah;
            }
            $karyawan->update(['piutang' => $totalpiutang]);
        }
    }

    public function restored(Accjurnalkhusus $accjurnalkhusus)
    {
        //
    }

    public function forceDeleted(Accjurnalkhusus $accjurnalkhusus)
    {
        //
    }
}
