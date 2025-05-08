<?php

namespace App\Http\Controllers;

use App\Models\Toko; // Import model Toko Anda
use Illuminate\View\View; // Import untuk type hinting

class HeadingController extends Controller
{
    /**
     * Mengambil dan mengirimkan data toko untuk kop surat.
     *
     * @return View
     */
    public function getHeaderData(): View
    {
        $toko = Toko::first(); // Ambil data toko pertama

        if ($toko) {
            $data = [
                'logo' => $toko->logo,
                'nama_toko' => $toko->nama_toko,
                'alamat' => $toko->alamat,
                'kota' => $toko->kota,
                'telp' => $toko->telp,
                'wa' => $toko->wa,
            ];
        } else {
            // Handle jika data toko tidak ditemukan
            $data = [
                'logo' => 'images/default_logo.png',
                'nama_toko' => 'Nama Toko Default',
                'alamat' => 'Alamat Default',
                'kota' => 'Kota Default',
                'telp' => 'Telepon Default',
                'wa' => 'WhatsApp Default',
            ];
        }

        return view('layout.heading', $data); // Kirim data ke view 'header'
    }
}
