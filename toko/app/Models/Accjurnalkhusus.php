<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accjurnalkhusus extends Model
{
    protected $table = 'accjurnalkhusus';
    protected $guarded = ['id'];

    public function Accperkiraan()
    {
        return $this->belongsTo('\App\Models\Accperkiraan', 'kode_acc', 'kode_acc');
    }
    public function AccperkiraanLawan()
    {
        return $this->belongsTo('App\Models\Accperkiraan', 'kode_lawan', 'kode_acc');
    }
    public function User()
    {
        return $this->belongsTo(Users::class);
    }
    public function Karyawan()
    {
        return $this->belongsTo('App\Models\User', 'karyawan_id', 'id');
    }
}
