<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pjdt extends Model
{
    protected $table = 'pjdt';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(Users::class);
    }
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }
    // public function dopjdt()
    // {
    //     return $this->belongsTo(Dopjdt::class);
    // }
    public function pjhd()
    {
        return $this->belongsTo(Pjhd::class);
    }
}
