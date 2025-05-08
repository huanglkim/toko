<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogOpr extends Model
{
    protected $table = 'log_opr';
    protected $guarded = ['id'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
