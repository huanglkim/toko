<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jenisbarang extends Model
{
    use SoftDeletes;

    protected $table = 'jenisbarang';
    protected $guarded = ['id'];


    // public function Barang()
    // {
    //     return $this->hasMany(Barang::class);
    // }
}
