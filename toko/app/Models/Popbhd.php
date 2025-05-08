<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Popbhd extends Model
{

    protected $table = 'popbhd';
    protected $guarded = ['id'];

    public function scopeAktif($query)
    {
        return $query->where('status', 1);
    }
    public function suplier()
    {
        return $this->belongsTo(Suplier::class)
            ->withDefault([
                'nama' => 'Tanpa Suplier',
                'kode' => 'X',
            ]);
    }
    public function useredit()
    {
        return $this->belongsTo('App\Models\Users', 'useredit_id', 'id')
            ->withDefault([
                'nama' => '',
                'username' => '',
            ]);
    }
    public function user()
    {
        return $this->belongsTo(Users::class);
    }
    public function popbdt()
    {
        return $this->hasMany(Popbdt::class);
    }
    public function pbhd()
    {
        return $this->hasMany(Pbhd::class);
    }
    public function kodekas()
    {
        return $this->belongsTo('App\Models\Accperkiraan', 'kode_acc_kas', 'kode_acc')
            ->withDefault([
                'nama_acc' => '',
            ]);
    }
}
