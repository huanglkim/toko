<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangin extends Model
{
    protected $table = 'barangin';
    protected $guarded = ['id'];
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function user()
    {
        return $this->belongsTo(Users::class)->withTrashed();
    }
    // public function TransaksiIn()
    // {
    //     return $this->belongsTo(TransaksiIn::class);
    // }
}
