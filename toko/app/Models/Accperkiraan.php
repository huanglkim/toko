<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accperkiraan extends Model
{
    protected $table = 'accperkiraan';
    protected $guarded = ['id'];

    public function scopeInduk($query, $induk)
    {
        return $query->where('induk_acc', $induk)->where('tipe', 'D')->orderBy('induk_acc', 'ASC');
    }
    public function scopeKelompok($query, $kelompok)
    {
        return $query->where('kelompok', $kelompok)->where('tipe', 'D')->orderBy('induk_acc', 'ASC');
    }
    //induk 
    //pendapatan = 4-1000
    //hpp = 5-1000
    //BOP = 5-2000
    //biaya = 6-1000
    //pendapatan lain-lain = 7-1000
    //beban lain-lain = 7-2000
    public function scopePendapatan($query)
    {
        return $query->where('induk_acc', '4-1000')->where('tipe', 'D');
    }
    public function scopeHpp($query)
    {
        return $query->where('induk_acc', '5-1000')->where('tipe', 'D');
    }
    public function scopeBop($query)
    {
        return $query->where('induk_acc', '5-2000')->where('tipe', 'D');
    }
    public function scopeBiaya($query)
    {
        return $query->where('kelompok', '6')->where('tipe', 'D');
    }
    public function scopePendapatanLain($query)
    {
        return $query->where('induk_acc', '7-1000')->where('tipe', 'D');
    }
    public function scopeBiayaLain($query)
    {
        return $query->where('induk_acc', '7-2000')->where('tipe', 'D');
    }

    public function accmutasi()
    {
        return $this->hasMany(Accmutasi::class, 'kode_acc', 'kode_acc');
    }

    public function accsa()
    {
        return $this->hasMany(Acc_sa::class, 'kode_acc', 'kode_acc');
    }
}
