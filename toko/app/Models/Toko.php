<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    protected $table = 'toko';
    protected $guarded = ['id'];
    // public function AccPeriode()
    // {
    //     return $this->belongsTo(AccPeriode::class);
    // }
    // public function AccSaldoAwal()
    // {
    //     return $this->belongsTo(AccSaldoAwal::class);
    // }
}
