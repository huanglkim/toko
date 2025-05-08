<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acc_sa extends Model
{
    protected $table = 'acc_sa';
    protected $guarded = ['id'];

    public function accperkiraan()
    {
        return $this->belongsTo(Accperkiraan::class, 'kode_acc', 'kode_acc');
    }
}
