<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{

    protected $table = 'barang';
    protected $guarded = ['id'];

    public function scopeAktif($query)
    {
        return $query->where('status', 1);
    }
    public function suplier()
    {
        return $this->belongsTo(Suplier::class)
            ->withDefault([
                'nama' => '-',
                'kode' => 'X',
            ]);
    }
    public function suplierterakhir()
    {
        return $this->belongsTo('App\Models\Suplier', 'suplierterakhir_id', 'id')
            ->withDefault([
                'nama' => '-',
                'kode' => 'X',
            ]);
    }
    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
    public function merkbarang()
    {
        return $this->belongsTo(Merkbarang::class);
    }
    public function jenisbarang()
    {
        return $this->belongsTo(Jenisbarang::class);
    }
    // public function BarangOut()
    // {
    //     return $this->hasMany(BarangOut::class);
    // }
    // public function BarangIn()
    // {
    //     return $this->hasMany(BarangIn::class);
    // }
    // public function FotoBarang()
    // {
    //     return $this->hasMany(FotoBarang::class);
    // }
    // public function FotoBarang1()
    // {
    //     return $this->hasOne(FotoBarang::class);
    // }
}
