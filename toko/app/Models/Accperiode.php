<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccPeriode extends Model
{
    protected $table = 'acc_periode';
    protected $guarded = ['id'];
    public function scopeAwal($query, $id)
    {
        return $query->where('id', $id)->value('awal');
    }
    public function scopeAkhir($query, $id)
    {
        return $query->where('id', $id)->value('Akhir');
    }
}
