<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pbdt extends Model
{

    protected $table = 'pbdt';
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
    public function popbdt()
    {
        return $this->belongsTo(Popbdt::class);
    }
    public function pbhd()
    {
        return $this->belongsTo(Pbhd::class);
    }
}
