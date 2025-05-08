<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CetakBarcode extends Model
{
    protected $table = 'cetak_barcode';
    protected $guarded = ['id'];

    public function Barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
