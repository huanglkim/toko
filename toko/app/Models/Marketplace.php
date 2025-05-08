<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marketplace extends Model
{
    protected $table = 'marketplace';
    protected $guarded = ['id'];

    public function pjhd($query)
    {
        return $this->hasMany(Pjhd::class);
    }
}
