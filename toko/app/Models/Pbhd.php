<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pbhd extends Model
{

    protected $table = 'pbhd';
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
    public function popbhd()
    {
        return $this->belongsTo(Popbhd::class)->withDefault([
            'nama' => 'DIRECT PB',
        ]);
    }
    public function pbdt()
    {
        return $this->hasMany(Pbdt::class);
    }
    public function kodeppn()
    {
        return $this->belongsTo('App\Models\Accperkiraan', 'kode_acc_ppn', 'kode_acc')
            ->withDefault([
                'nama_acc' => '',
            ]);
    }
    public function kodedpp()
    {
        return $this->belongsTo('App\Models\Accperkiraan', 'kode_acc_dpp', 'kode_acc')
            ->withDefault([
                'nama_acc' => '',
            ]);
    }
    public function kodedp()
    {
        return $this->belongsTo('App\Models\Accperkiraan', 'kode_acc_dp', 'kode_acc')
            ->withDefault([
                'nama_acc' => '',
            ]);
    }
    public function kodekas()
    {
        return $this->belongsTo('App\Models\Accperkiraan', 'kode_acc_kas', 'kode_acc')
            ->withDefault([
                'nama_acc' => '',
            ]);
    }
    public function kodehutang()
    {
        return $this->belongsTo('App\Models\Accperkiraan', 'kode_acc_hutang', 'kode_acc')
            ->withDefault([
                'nama_acc' => '',
            ]);
    }
}
