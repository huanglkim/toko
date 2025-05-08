<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AksesJabatan extends Model
{
    protected $table = 'akses_jabatan';
    protected $guarded = ['id'];
    public function Role()
    {
        return $this->belongsTo(Role::class);
    }
    public function Akses()
    {
        return $this->belongsTo(Akses::class);
    }
}
