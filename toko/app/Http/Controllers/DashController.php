<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Spk;
// use App\Models\TransaksiOut;
// use App\Models\TransaksiIn;
// use App\Models\Barang;
// use App\Models\BarangOut;
// use App\Models\Pelanggan;
// use App\Models\JasaOut;
// use App\Models\Jasa;
use App\Models\Menus;
use App\Models\MenuRoles;
use App\Models\Menufav;

class DashController extends Controller
{
    public function dashadmin()
    {
        return view('dash.karyawan');
    }
    public function detailmenudashboard(Request $request)
    {
        $cari = $request->cari;
        $caris = [];
        if ($cari != '') {
            $caris = explode(",", $cari);
        }
        $induks = Menus::select('induk')
            ->groupBy('induk')
            ->orderBy('induk', 'DESC')
            ->get();
        $menu_ids = MenuRoles::where('role_id', Auth()->user()->role_id)->pluck('menu_id');
        $listmenuuser = MenuRoles::where('role_id', Auth()->User()->role_id)->get();
        $favmenus = Menufav::where('user_id', Auth()->User()->id)->get();

        return view('dash.newmenu', compact([
            'induks',
            'menu_ids',
            'caris',
            'favmenus',
            'listmenuuser'
        ]));
    }

    // public function menufav(Request $request)
    // {
    //     $user_id = Auth()->User()->id;
    //     $menu_id = $request->menu_id;
    //     $menufav_id = $request->menufav_id;
    //     if ($menufav_id == 0) {
    //         $menufav = Menufav::where('user_id', $user_id)->where('menu_id', $menu_id)->first();
    //         if ($menufav) {
    //             $data = [
    //                 'success' => 0,
    //                 'pesan' => 'sudah fav'
    //             ];
    //             return $data;
    //         }
    //         $input['user_id'] = $user_id;
    //         $input['menu_id'] = $menu_id;
    //         Menufav::create($input);
    //         $data = [
    //             'success' => 1,
    //             'pesan' => 'OK'
    //         ];
    //         return $data;
    //     } else {
    //         Menufav::destroy($menufav_id);
    //         $data = [
    //             'success' => 1,
    //             'pesan' => 'OK'
    //         ];
    //         return $data;
    //     }
    //     $data = [
    //         'success' => 0,
    //         'pesan' => 'ERROR'
    //     ];
    //     return $data;
    // }

    // public function favunfav($id)
    // {
    //     $menu = Menus::find($id);
    //     if ($menu->fav == 0) {
    //         $input['fav'] = 1;
    //     } else {
    //         $input['fav'] = 0;
    //     }
    //     $menu->update($input);
    //     $data = [
    //         'success' => 1,
    //         'pesan' => 'OK'
    //     ];
    //     return $data;
    // }
    // public function overview()
    // {
    //     $TotalSpk = Spk::count();
    //     $TotalSpkHari = Spk::whereDate('created_at', now())
    //         ->count();
    //     $TotalSpkBulan = Spk::whereMonth('created_at', now())
    //         ->whereYear('created_at', now())
    //         ->count();
    //     $TrOutBulanIni = TransaksiOut::whereMonth('tanggal', now())
    //         ->whereYear('tanggal', now())
    //         ->whereIn('tipe', ['KSR', 'PJ'])
    //         ->get();
    //     $TrOutHariIni = TransaksiOut::whereDate('tanggal', now())
    //         ->whereIn('tipe', ['KSR', 'PJ'])
    //         ->get();
    //     $KasirCashHari = $TrOutHariIni->sum('kas');
    //     $KasirTfHari = $TrOutHariIni->sum('bank');
    //     $KasirPiutangHari = $TrOutHariIni->sum('piutang');
    //     $PjBarangBulanIni = $TrOutBulanIni->sum('pendapatan_barang');
    //     $PjJasaBulanIni = $TrOutBulanIni->sum('pendapatan_jasa');
    //     $TrInBulanIni = TransaksiIn::whereMonth('tanggal', now())
    //         ->whereYear('tanggal', now())
    //         ->where('tipe', 'PB')
    //         ->get();
    //     $PbBulanIni = $TrInBulanIni->sum('persediaan');

    //     $StokMinimums = Barang::where('status', '=', 1)
    //         ->where('minimum', "!=", 0)
    //         ->whereColumn('stok', '<=', 'minimum')
    //         ->orderBy('stok', 'ASC')
    //         ->limit(10)
    //         ->get();

    //     $PalingLakus = BarangOut::whereIn('tipe', ['PJ', 'KSR'])
    //         ->groupBy('barang_id')
    //         ->selectRaw('barang_id, sum(qty) as totalqty')
    //         ->orderBy('qty', 'DESC')
    //         ->limit(10)
    //         ->get();
    //     $KotaPelanggan = Pelanggan::groupBy('kota')
    //         ->selectRaw('kota, count(*) as total')
    //         ->orderBy('total', 'DESC')
    //         ->limit(10)
    //         ->get();
    //     $KunjunganPelanggan = Spk::groupBy('pelanggan_id')
    //         ->selectRaw('pelanggan_id, count(*) as total')
    //         ->orderBy('total', 'DESC')
    //         ->limit(10)
    //         ->get();

    //     $jualrugi = Barang::where('harga1', "!=", 0)
    //         ->whereColumn('harga1', '<=', 'harga_terakhir')
    //         ->where('status', '=', 1)
    //         ->where('deleted_at', '=', null)
    //         ->limit(10)
    //         ->get();

    //     $id_jasa = Jasa::where('reminder', 1)->pluck('id');
    //     $jangka = datapengaturan('reminder_service');
    //     $waktu_service = date('Y-m-d', strtotime(date('Y-m-d') . "-" . $jangka . "month"));
    //     $jasanotif = JasaOut::whereIn('jasa_id', $id_jasa)
    //         ->where('status_notif', 0)
    //         ->whereDate('created_at', '<=', $waktu_service)
    //         ->get();
    //     return view('dash.admin', compact([
    //         'TotalSpk',
    //         'TotalSpkHari',
    //         'TotalSpkBulan',
    //         'PjBarangBulanIni',
    //         'PjJasaBulanIni',
    //         'PbBulanIni',
    //         'KasirCashHari',
    //         'KasirTfHari',
    //         'KasirPiutangHari',
    //         'StokMinimums',
    //         'PalingLakus',
    //         'KotaPelanggan',
    //         'KunjunganPelanggan',
    //         'jasanotif',
    //         'waktu_service',
    //         'jualrugi'

    //     ]));
    // }
    // public function dashkasir()
    // {
    //     return view('dash.kasir');
    // }
    // public function dashkaryawan()
    // {
    //     return view('dash.karyawan');
    // }
}
