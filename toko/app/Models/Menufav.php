<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menufav extends Model
{

    protected $table = 'menufav';
    protected $guarded = ['id'];
    public function menu()
    {
        return $this->belongsTo(Menus::class);
    }
}
