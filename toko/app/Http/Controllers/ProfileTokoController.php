<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileTokoController extends Controller
{
    // gunakan constructor untuk inisialisasi, jadi tidak perlu panggil model Toko berulang kali
    private $toko;

    public function __construct()
    {
        $this->toko = Toko::first(); // Asumsi hanya ada satu toko, atau ingin ambil toko pertama.
                                     // Jika ada logika lain untuk menentukan toko mana yang diakses, sesuaikan di sini.
    }
    public function show()
    {
        // $toko = Toko::first(); // Tidak perlu ini lagi
        return view('users.profiletoko', ['toko' => $this->toko]); // compact tidak efisien untuk satu variabel
    }

     public function edit($id)  // Tambahkan parameter $id
    {
       $toko = Toko::findOrFail($id);
        return view('users.editprofile-toko', ['toko' => $toko]);
    }

    public function update(Request $request)
    {
        // $toko = Toko::first(); // Tidak perlu ini lagi

        $validator = Validator::make($request->all(), [
            'nama_toko' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'kota' => ['nullable', 'string', 'max:255'],
            'npwp' => ['nullable', 'string', 'max:20'],
            'telp' => ['nullable', 'string', 'max:20'],
            'wa' => ['nullable', 'string', 'max:20'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->toko->nama_toko = $request->nama_toko;
        $this->toko->alamat = $request->alamat;
        $this->toko->kota = $request->kota;
        $this->toko->npwp = $request->npwp;
        $this->toko->telp = $request->telp;
        $this->toko->wa = $request->wa;

        if ($request->hasFile('logo')) {
            // Delete old image if exists
            if ($this->toko->logo) {
                Storage::disk('public')->delete($this->toko->logo);
            }
    
            $image = $request->file('logo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName); // Simpan di direktori 'images' di dalam 'public'
            $this->toko->logo = 'images/' . $imageName; // Simpan path relatif ke database
        }
    
        $this->toko->save();
    
        return redirect()->route('profiletoko.show')->with('success', 'Logo toko berhasil diperbarui.');
    }
}
