<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akses extends Model
{
    protected $table = 'akses';
    protected $guarded = ['id'];
    public function AksesJabatan()
    {
        return $this->hasMany(AksesJabatan::class);
    }
}
