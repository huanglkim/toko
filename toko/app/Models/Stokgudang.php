<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stokgudang extends Model
{

    protected $table = 'stokgudang';
    protected $guarded = ['id'];


    // public function Barang()
    // {
    //     return $this->hasMany(Barang::class);
    // }
}
