<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accmutasi extends Model
{
    protected $table = 'accmutasi';
    protected $guarded = ['id'];
    public function Accperkiraan()
    {
        return $this->belongsTo(Accperkiraan::class);
    }

    public function acc_sa()
    {
        return $this->belongsTo(Acc_sa::class, 'kode_acc', 'kode_acc');
    }


    public function scopeDebetValue($query, $tahun, $bulan, $toko_id,  $kode_acc)
    {
        $acc = $query->where('tahun', $tahun)->where('bulan', $bulan)->where('toko_id', $toko_id)
            ->where('kode_acc', $kode_acc)->first();
        if ($acc) {
            $result = $acc->debet - $acc->kredit;
        } else {
            $result = 0;
        }
        return $result;
    }
    public function scopeKreditValue($query, $tahun, $bulan, $toko_id,  $kode_acc)
    {
        $acc = $query->where('tahun', $tahun)->where('bulan', $bulan)->where('toko_id', $toko_id)
            ->where('kode_acc', $kode_acc)->first();
        if ($acc) {
            $result = $acc->kredit - $acc->debet;
        } else {
            $result = 0;
        }
        return $result;
    }
    public function scopeDebetTotal($query, $tahun, $bulan, $toko_id,  $kode_acc_array)
    {
        $acc = $query->where('tahun', $tahun)->where('bulan', $bulan)->where('toko_id', $toko_id)
            ->whereIn('kode_acc', $kode_acc_array)->get();
        if (!empty($acc)) {
            $result = $acc->sum('debet') - $acc->sum('kredit');
        } else {
            $result = 0;
        }
        return $result;
    }
    public function scopeKreditTotal($query, $tahun, $bulan, $toko_id,  $kode_acc_array)
    {
        $acc = $query->where('tahun', $tahun)->where('bulan', $bulan)->where('toko_id', $toko_id)
            ->whereIn('kode_acc', $kode_acc_array)->get();
        if (!empty($acc)) {
            $result = $acc->sum('kredit') - $acc->sum('debet');
        } else {
            $result = 0;
        }
        return $result;
    }
    public function scopeMutasiAcc($query, $toko_id, $PeriodeAwal, $PeriodeAkhir, $kode_acc)
    {
        $acc = $query->where('toko_id', $toko_id)->whereBetween('periode', [$PeriodeAwal, $PeriodeAkhir])
            ->where('kode_acc', $kode_acc);
        if (!empty($acc)) {
            $result = 0;
        } else {
            $result = 0;
        }
        return $acc;
    }
}
