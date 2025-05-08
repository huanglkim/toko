<?php


function menuinduk($induk)
{

    $menuuser = \App\Models\Menus::where('induk', $induk)->pluck('id');
    // $menu_id = array();
    // foreach ($menuuser as $mu) {
    //     $menu_id[] = $mu->id;
    // }
    $usermenu = \App\Models\MenuRoles::where('role_id', Auth()->user()->role_id)
        ->whereIn('menu_id', $menuuser)
        ->orderBy('menu_id', 'ASC')
        ->get();

    return $usermenu;
}
function cekmenuuser($menu_id)
{
    $usermenu = \App\Models\MenuRoles::where('role_id', Auth()->user()->role_id)
        ->where('menu_id', $menu_id)
        ->first();
    if ($usermenu) {
        return true;
    }
    return false;
}
function RupiahNonRp($angka)
{
    $hasil_rupiah = number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}
function Rupiah($angka)
{
    $hasil_rupiah = "Rp. " . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
}
function Rupiah0($angka)
{
    $hasil_rupiah = "Rp. " . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}
function Toko($id)
{
    $Toko = App\Models\Toko::find($id);
    return $Toko;
}

function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = array("", "SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM", "TUJUH", "DELAPAN", "SEMBILAN", "SEPULUH", "SEBELAS");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " BELAS";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai / 10) . " PULUH" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " SERATUS" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . " RATUS" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . " RIBU" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . " JUTA" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . " MILYAR" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . " TRILYUN" . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
}

function Terbilang($nilai)
{
    if ($nilai < 0) {
        $hasil = "MINUS " . trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai)) . ' RUPIAH';
    }
    return $hasil;
}

function BulanArray()
{
    $bulan_array = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];
    return $bulan_array;
}
function TahunArray()
{
    $mulai = date('Y') + 1;
    $akhir = date('Y') - 25;
    $tahun_array = range($mulai, $akhir);
    return $tahun_array;
}

function TanggalIndo($date)
{
    $tanggal = date('d-m-Y', strtotime($date));
    return $tanggal;
}

function TanggalJam($date)
{
    $tanggal = date('d-m-Y H:i:s', strtotime($date));
    return $tanggal;
}

function arraytanggal($bulan, $tahun)
{
    $tanggal = strtotime($tahun . '-' . $bulan . '-1');
    $lastdate = date('t', $tanggal);
    $dates_month = array();

    for ($i = 1; $i <= $lastdate; $i++) {
        $dates_month[$i] = $i;
    }

    return $dates_month;
}

function datetotanggal($date)
{
    if ($date == null) {
        return '';
    }
    $tanggal = date('d-m-Y', strtotime($date));
    return $tanggal;
}
function datetosimple($date)
{
    if ($date == null) {
        return '';
    }
    $tanggal = date('d-m', strtotime($date));
    return $tanggal;
}
function tanggaltodate($inputDate)
{
    $formats = ['dmY', 'd-m-Y', 'dmy'];

    foreach ($formats as $format) {
        $date = DateTime::createFromFormat($format, $inputDate);
        if ($date && $date->format($format) == $inputDate) {
            return $date->format('Y-m-d');
        }
    }

    // If no format matches, return an error message
    return '';
}

function publicfolder()
{
    $folder = cache()->rememberForever('publicfolder_path', function () {
        return \App\Models\Globalconfig::where('nama_config', 'publicfolder')->value('data_config') ?? '/public/';
    });

    $folder = rtrim($folder, '/');
    return url($folder);
}
function globalconfigdata($namaconfig)
{
    $data = \App\Models\Globalconfig::where('nama_config', $namaconfig)->value('data_config');
    return $data;
}

function kode($id)
{
    if ($id == 1) { //pelanggan
        $pel = App\Models\Pelanggan::withTrashed()->latest('id')->first();
        if (empty($pel)) {
            $urut = 1;
        } else {
            $urut = $pel->id + 1;
        }
        $latest_id = 'PLG' . $urut;
    }
    if ($id == 2) { //supplier
        $sup = App\Models\Suplier::withTrashed()->latest('id')->first();
        if (empty($sup)) {
            $urut = 1;
        } else {
            $urut = $sup->id + 1;
        }
        $latest_id = 'SUP' . $urut;
    }
    if ($id == 3) { //kode PoPB
        $po = App\Models\Popbhd::latest('id')->first();
        if (empty($po)) {
            $id = 1;
        } else {
            $id = $po->id + 1;
        }
        $latest_id = 'PO/' . date('m') . date('y') . '/' . $id;
    }
    if ($id == 4) { //kode PB
        $pb = App\Models\Pbhd::latest('id')->first();
        if (empty($pb)) {
            $id = 1;
        } else {
            $id = $pb->id + 1;
        }
        $latest_id = 'PB/' . date('m') . date('y') . '/' . $id;
    }
    if ($id == 5) { //kode PJ
        $pj = App\Models\Pjhd::where('tipe', 'PJ')->latest('id')->first();
        if (empty($pj)) {
            $id = 1;
        } else {
            $id = $pj->id + 1;
        }
        $user_id = Auth()->User()->id;
        $latest_id = 'PJ/' . $user_id . '/' . date('m') . date('y') . '/' . $id;
    }
    if ($id == 6) { //kode KSR
        $pj = App\Models\Pjhd::where('tipe', 'KSR')->latest('id')->first();
        if (empty($pj)) {
            $id = 1;
        } else {
            $id = $pj->id + 1;
        }
        $user_id = Auth()->User()->id;
        $latest_id = 'KSR/' . $user_id . '/' . date('m') . date('y') . '/' . $id;
    }
    if ($id == 7) { //kode KSR MPL
        $pj = App\Models\Pjhd::where('tipe', 'MPL')->latest('id')->first();
        if (empty($pj)) {
            $id = 1;
        } else {
            $id = $pj->id + 1;
        }
        $user_id = Auth()->User()->id;
        $latest_id = 'MPL/' . $user_id . '/' . date('m') . date('y') . '/' . $id;
    }
    if ($id == 12) {
        $dt = \App\Models\Barang::latest('id')->first();
        if (empty($dt)) {
            $urut = 10001;
        } else {
            $urut = $dt->id + 10001;
        }
        $latest_id = 'WU' . $urut;
    }

    return $latest_id;
}
function kodejurnal($id)
{
    if ($id == 1) { //kas keluar
        $ack = App\Models\AccJurnalKhusus::latest('id')->first();
        if (empty($ack)) {
            $urut = 1;
        } else {
            $urut = $ack->id + 1;
        }
        $invoice = date('m') . date('y') . $urut;
    }
    return $invoice;
}
function hitungpajak($total_harga, $qty, $persenpajak, $jenisppn)
{
    $totalppn = 0;
    $totalhpp = $total_harga;
    $ppn = 0;
    $hpp = ($qty != 0 && $totalhpp != 0) ? $totalhpp / $qty : 0;

    if ($jenisppn == 'exclude') {
        $totalppn = $total_harga * $persenpajak / 100; // Total tax
        $ppn = $qty != 0 ? $totalppn / $qty : 0; // Tax per item
    } elseif ($jenisppn == 'include') {
        $totalhpp = ($persenpajak + 100) != 0
            ? 100 / ($persenpajak + 100) * $total_harga
            : 0; // Base price without tax
        $hpp = ($qty != 0 && $totalhpp != 0) ? $totalhpp / $qty : 0; // HPP per item
        $totalppn = $total_harga - $totalhpp; // Total tax
        $ppn = $qty != 0 ? $totalppn / $qty : 0; // Tax per item
    }

    return compact('totalhpp', 'totalppn', 'ppn', 'hpp');
}
function hitungpajakjual($total_harga, $qty, $persenpajak, $jenisppn)
{
    $totalppn = 0;
    $totaldpp = $total_harga;
    $ppn = 0;
    $dpp = ($qty != 0 && $totaldpp != 0) ? $totaldpp / $qty : 0;

    if ($jenisppn == 'exclude') {
        $totalppn = $total_harga * $persenpajak / 100; // Total tax
        $ppn = $qty != 0 ? $totalppn / $qty : 0; // Tax per item
    } elseif ($jenisppn == 'include') {
        $totaldpp = ($persenpajak + 100) != 0
            ? 100 / ($persenpajak + 100) * $total_harga
            : 0; // Base price without tax
        $dpp = ($qty != 0 && $totaldpp != 0) ? $totaldpp / $qty : 0; // HPP per item
        $totalppn = $total_harga - $totaldpp; // Total tax
        $ppn = $qty != 0 ? $totalppn / $qty : 0; // Tax per item
    }

    return compact('totaldpp', 'totalppn', 'ppn', 'dpp');
}

function acckasbank()
{
    $KasBank = App\Models\Accperkiraan::select('kode_acc', 'nama_acc', 'id')
        ->where('kas', 1)
        ->orWhere('bank', 1)
        ->get();
    return $KasBank;
}
function acckas()
{
    $KasBank = App\Models\Accperkiraan::select('kode_acc', 'nama_acc', 'id')
        ->where('kas', 1)
        ->get();
    return $KasBank;
}
function accbank()
{
    $KasBank = App\Models\Accperkiraan::select('kode_acc', 'nama_acc', 'id')
        ->where('bank', 1)
        ->get();
    return $KasBank;
}
