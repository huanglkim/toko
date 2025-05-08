<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pjhd;
use App\Models\Pjdt;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\Accjurnal;
use App\Traits\PjTrait;

class KasirController extends Controller
{
    use PjTrait;

    public function kasir()
    {
        $tipe = 'KSR';
        $jumlahpjpending = Pjhd::where('status', 1)->where('tipe', $tipe)->count();
        $judul = 'PJ TUNAI';
        return view('penjualan.penjualan', compact(['jumlahpjpending', 'tipe', 'judul']));
    }
    public function tambahksrbaru()
    {
        $input = [
            'invoice' => kode(6), // kode PJ
            'tanggal' => now(),
            'pelanggan_id' => 1,
            'tipe' => 'KSR',
            'user_id' => Auth::id(),
            'persenpajak' => globalconfigdata('ppnpj'), // kode po
            'tanggal_kirim' => now(),
            'keterangan' => '-',
            'uuid' => Str::uuid(),
        ];
        $pjhd = Pjhd::create($input);

        return [
            'success' => 1,
            'uuid' => $pjhd->uuid
        ];
    }
    public function footerksr($id)
    {
        $pjhd = Pjhd::findOrFail($id);
        $dppb = $pjhd->dd;
        $totalakhir = $pjhd->dpp + $pjhd->ppn - $pjhd->potongan;

        $totalakhir; // - $dppo; // Amount that must be paid
        $kas = $pjhd->kas; // Cash payment
        $bank = $pjhd->bank; // tf payment
        $piutang = $totalakhir - $kas - $bank; // Outstanding debt

        if ($piutang < 0) {
            if ($bank > $totalakhir) {
                $input['kas'] = 0;
                $input['bank'] = $totalakhir;
                $input['piutang'] = 0;
            } else {
                $input['kas'] = $totalakhir - $bank;
                $input['piutang'] = 0;
            }
            $input['status_piutang'] = 2; // Fully paid
        } else {
            $input['piutang'] = $piutang;
            $input['status_piutang'] = 1; // Debt exists
        }

        $pjhd->update($input);

        return view('penjualan.footerksr', compact('pjhd', 'totalakhir'));
    }
}
