<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccSaldoAwal extends Model
{
    protected $table = 'acc_saldo_awal';
    protected $guarded = ['id'];
    public function AccPeriode()
    {
        return $this->belongsTo(AccPeriode::class);
    }
    public function scopeKreditValue($query, $acc_periode_id,  $kode_acc)
    {
        $acc = $query->where('acc_periode_id', $acc_periode_id)->where('kode_acc', $kode_acc)->first();
        if ($acc) {
            $result = $acc->kredit - $acc->debet;
        } else {
            $result = 0;
        }
        return $result;
    }
    public function scopeDebetValue($query, $acc_periode_id,  $kode_acc)
    {
        $acc = $query->where('acc_periode_id', $acc_periode_id)->where('kode_acc', $kode_acc)->first();
        if ($acc) {
            $result = $acc->debet - $acc->kredit;
        } else {
            $result = 0;
        }
        return $result;
    }
}
