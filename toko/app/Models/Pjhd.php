<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pjhd extends Model
{
    protected $table = 'pjhd';
    protected $guarded = ['id'];

    public function scopeAktif($query)
    {
        return $query->where('status', 1);
    }
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class)
            ->withDefault([
                'nama' => 'UMUM',
                'kode' => 'X',
            ]);
    }
    public function marketplace()
    {
        return $this->belongsTo(Marketplace::class)
            ->withDefault([
                'nama_marketplace' => '-',
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
    // public function dopjhd()
    // {
    //     return $this->belongsTo(Dopjhd::class)->withDefault([
    //         'nama' => 'DIRECT PJ',
    //     ]);
    // }
    public function pjdt()
    {
        return $this->hasMany(Pjdt::class);
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
    public function kodebank()
    {
        return $this->belongsTo('App\Models\Accperkiraan', 'kode_acc_bank', 'kode_acc')
            ->withDefault([
                'nama_acc' => '',
            ]);
    }
    public function kodepiutang()
    {
        return $this->belongsTo('App\Models\Accperkiraan', 'kode_acc_piutang', 'kode_acc')
            ->withDefault([
                'nama_acc' => '',
            ]);
    }
}
