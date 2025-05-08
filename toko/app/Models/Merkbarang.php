<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merkbarang extends Model
{
    use SoftDeletes;

    protected $table = 'merkbarang';
    protected $guarded = ['id'];


    // public function Barang()
    // {
    //     return $this->hasMany(Barang::class);
    // }
}
