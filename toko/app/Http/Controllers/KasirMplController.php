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
use App\Models\Marketplace;
use App\Traits\PjTrait;

class KasirMplController extends Controller
{
    use PjTrait;

    public function kasir()
    {
        $tipe = 'MPL';
        $jumlahpjpending = Pjhd::where('status', 1)->where('tipe', $tipe)->count();
        $judul = 'KASIR MARKETPLACE';
        return view('penjualan.penjualan', compact(['jumlahpjpending', 'tipe', 'judul']));
    }
    public function tambahksrbaru()
    {
        $input = [
            'invoice' => kode(7), // kode PJ
            'tanggal' => now(),
            'pelanggan_id' => 1,
            'tipe' => 'MPL',
            'user_id' => Auth::id(),
            'persenpajak' => globalconfigdata('ppnpj'), // kode po
            'tanggal_kirim' => now(),
            'keterangan' => '-',
            'kode_acc_kas' => '1-1112',
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
        // $kas = $pjhd->kas; // Cash payment
        // $bank = $pjhd->bank; // tf payment
        // $piutang = $totalakhir - $kas - $bank; // Outstanding debt

        // if ($piutang < 0) {
        //     if ($bank > $totalakhir) {
        //         $input['kas'] = 0;
        //         $input['bank'] = $totalakhir;
        //         $input['piutang'] = 0;
        //     } else {
        //         $input['kas'] = $totalakhir - $bank;
        //         $input['piutang'] = 0;
        //     }
        //     $input['status_piutang'] = 2; // Fully paid
        // } else {
        //     $input['piutang'] = $piutang;
        //     $input['status_piutang'] = 1; // Debt exists
        // }
        $input['kas'] = $totalakhir;
        $input['status_piutang'] = 2; // Fully paid
        $pjhd->update($input);

        return view('penjualan.footerksrmpl', compact('pjhd', 'totalakhir'));
    }
    public function hitungbadminmpl(Request $request)
    {
        $pjhd = Pjhd::findOrFail($request->id);
        $marketplace_id = $pjhd->marketplace_id;
        $marketplace = Marketplace::find($marketplace_id);
        $input['admin_mpl'] = 0;
        $input['admin_lain'] = 0;
        if ($marketplace) {
            $tipe_admin_mpl = $pjhd->tipe_admin_mpl;
            if ($tipe_admin_mpl == 'exclude') {
                $total = $pjhd->total;
                $badm1 = $total * $marketplace->biaya_adm1 / 100;
                $badm2 = $total * $marketplace->biaya_adm2 / 100;
                $brplain = $marketplace->biayalainrp;
                $bongkir = $total * $marketplace->biaya_ongkir_persen / 100;
                if ($total >= $marketplace->biaya_max_ongkir_rp) {
                    $bongkir = $marketplace->biaya_ongkir_rp;
                }
                $admin_mpl = $badm1 + $badm2 + $brplain + $bongkir;
                $admin_lain = $pjhd->admin_lain;
                $input['admin_mpl'] = $admin_mpl;
                $input['admin_lain'] = $admin_lain;
            }
        }
        $pjhd->update($input);
        return $this->footerksr($pjhd->id);
    }
}
