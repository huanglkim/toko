<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accjurnal extends Model
{
    protected $table = 'accjurnal';
    protected $guarded = ['id'];
    public function accperkiraan()
    {
        return $this->belongsTo('App\Models\Accperkiraan', 'kode_acc', 'kode_acc');
    }
    public function accperkiraanLawan()
    {
        return $this->belongsTo('App\Models\Accperkiraan', 'kode_lawan', 'kode_acc' );
    }
    // public function transaksiIn()
    // {
    //     return $this->belongsTo('App\Models\TransaksiIn', 'invoice', 'invoice');
    // }
    // public function TransaksiOut()
    // {
    //     return $this->belongsTo('App\Models\TransaksiOut', 'invoice', 'invoice');
    // }
    public function accjurnalKhusus()
    {
        return $this->belongsTo('App\Models\AccjurnalKhusus', 'invoice', 'invoice');
    }
    // public function BayarHutang()
    // {
    //     return $this->belongsTo('App\Models\BayarHutang', 'invoice', 'invoice');
    // }
    // public function BayarPiutang()
    // {
    //     return $this->belongsTo('App\Models\BayarPiutang', 'invoice', 'invoice');
    // }
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }
    //3-3000 laba tahun berjalan
    
}
