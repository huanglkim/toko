<?php

namespace App\Observers;

use App\Models\Barangin;
use App\Models\Barang;
use App\Models\Stokgudang;

class BaranginObserver
{

    public function created(Barangin $barangin)
    {
        $Barang = Barang::find($barangin->barang_id);
        $gudang_id = $barangin->gudang_id;
        $stokgudang = Stokgudang::where('barang_id', $Barang->id)
            ->where('gudang_id', $gudang_id)->first();
        if (!$stokgudang) {
            Stokgudang::create([
                'barang_id' => $Barang->id,
                'gudang_id' => $gudang_id,
                'stok'  => $barangin->qty,
            ]);
        } else {
            $inputsg['stok'] = $stokgudang->stok + $barangin->qty;
            $stokgudang->update($inputsg);
        }
        if ($barangin->qty > 0) {
            $stok = $Barang->stok + $barangin->qty;
            $totalhpp = $Barang->hpp_total + $barangin->totalhpp;
            if ($stok == 0 || $totalhpp == 0) {
                $hpp = 0;
            } else {
                $hpp = $totalhpp / $stok; //rata2
            }
            $input['stok'] = $stok;
            $input['hpp_satuan'] = $hpp;
            $input['hpp_total'] = $totalhpp;
            if ($barangin->tipe == 'PB') {
                $input['harga_beli_terakhir'] = $barangin->hpp;
            }
            $Barang->update($input);
        }
    }

    public function updated(Barangin $barangin)
    {
        $Barang = Barang::find($barangin->barang_id);

        $selisihqty = $barangin->qty - $barangin->getOriginal('qty');
        $stok = $Barang->stok + $selisihqty;
        $selisihtotalhpp = $barangin->totalhpp - $barangin->getOriginal('totalhpp');
        $totalhpp = $Barang->hpp_total + $selisihtotalhpp;
        if ($stok == 0 || $totalhpp == 0) {
            $hpp = 0;
        } else {
            $hpp = $totalhpp / $stok;
        }
        $input['stok'] = $stok;
        $input['hpp_satuan'] = $hpp;
        $input['hpp_total'] = $totalhpp;
        $Barang->update($input);

        $gudang_id = $barangin->gudang_id;
        $stokgudang = Stokgudang::where('barang_id', $Barang->id)
            ->where('gudang_id', $gudang_id)->first();
        if (!$stokgudang) {
        } else {
            $inputsg['stok'] = $stokgudang->stok + $selisihqty;
            $stokgudang->update($inputsg);
        }
    }


    public function deleted(Barangin $barangin)
    {
        $Barang = Barang::find($barangin->barang_id);
        $gudang_id = $barangin->gudang_id;
        $stokgudang = Stokgudang::where('barang_id', $Barang->id)
            ->where('gudang_id', $gudang_id)->first();
        if (!$stokgudang) {
        } else {
            $inputsg['stok'] = $stokgudang->stok - $barangin->qty;
            $stokgudang->update($inputsg);
        }
        if ($barangin->qty > 0) {
            $stok = $Barang->stok - $barangin->qty;
            $totalhpp = $Barang->hpp_total - $barangin->totalhpp;
            if ($stok == 0 || $totalhpp == 0) {
                $hpp = 0;
            } else {
                $hpp = $totalhpp / $stok;
            }
            $input['stok'] = $stok;
            $input['hpp_satuan'] = $hpp;
            $input['hpp_total'] = $totalhpp;
            $Barang->update($input);
        }
    }

    public function restored(Barangin $barangin)
    {
        //
    }


    public function forceDeleted(Barangin $barangin)
    {
        //
    }
}
