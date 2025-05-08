<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Popbdt extends Model
{

    protected $table = 'popbdt';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(Users::class);
    }
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function popbhd()
    {
        return $this->belongsTo(Popbhd::class);
    }
    public function pbdt()
    {
        return $this->hasMany(Pbdt::class);
    }
}
