<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Satuan extends Model
{
    use SoftDeletes;

    protected $table = 'satuan';
    protected $guarded = ['id'];


    // public function Barang()
    // {
    //     return $this->hasMany(Barang::class);
    // }
}
