<?php

namespace App\Observers;

use App\Models\BarangOut;
use App\Models\Barang;

class BarangOutObserver
{
    public function created(BarangOut $barangOut)
    {
        $Barang = Barang::find($barangOut->barang_id);
        if ($barangOut->qty > 0) {
            $stok = $Barang->stok - $barangOut->qty;
            $totalhpp = $Barang->totalhpp - $barangOut->totalhpp;
            $input['stok'] = $stok;
            $input['totalhpp'] = $totalhpp;
            $Barang->update($input);
        }
    }

    public function updated(BarangOut $barangOut)
    {
        $Barang = Barang::find($barangOut->barang_id);
        $selisihqty = $barangOut->getOriginal('qty') + $Barang->stok;
        $stok = $selisihqty - $barangOut->qty;
        $selisihtotalhpp = $Barang->totalhpp + $barangOut->getOriginal('totalhpp');
        $totalhpp = $selisihtotalhpp  - $barangOut->totalhpp;
        if ($stok == 0 || $totalhpp == 0) {
            $hpp = 0;
        } else {
            $hpp = $totalhpp / $stok;
        }
        $input['stok'] = $stok;
        $input['totalhpp'] = $totalhpp;
        $input['hpp'] = $hpp;
        $Barang->update($input);
    }

    public function deleted(BarangOut $barangOut)
    {
        $Barang = Barang::find($barangOut->barang_id);
        if ($barangOut->qty > 0) {
            $stok = $Barang->stok + $barangOut->qty;
            $totalhpp = $Barang->totalhpp + $barangOut->totalhpp;
            if ($stok == 0 || $totalhpp == 0) {
                $hpp = 0;
            } else {
                $hpp = $totalhpp / $stok;
            }
            $input['hpp'] = $hpp;
            $input['stok'] = $stok;
            $input['totalhpp'] = $totalhpp;
            $Barang->update($input);
        }
    }

    public function restored(BarangOut $barangOut)
    {
        //
    }

    public function forceDeleted(BarangOut $barangOut)
    {
        //
    }
}
