<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fotobarang extends Model
{
    protected $table = 'fotobarang';
    protected $guarded = ['id'];

    public function Barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
