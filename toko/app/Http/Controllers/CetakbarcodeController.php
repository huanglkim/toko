<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CetakBarcode;
use App\Models\Barang;

class CetakbarcodeController extends Controller
{
    public function index()
    {
        return view('cetakbarcode.index');
    }
    public function tabelcetakbarcode()
    {
        $CetakBarcode = CetakBarcode::all();
        return view('cetakbarcode.tabel', compact(['CetakBarcode']));
    }
    public function show($id)
    {
        $CetakBarcode = CetakBarcode::findorfail($id);
        $CetakBarcode['nama_barang'] = $CetakBarcode->Barang->nama_barang;
        return $CetakBarcode;
    }
    public function store(Request $request)
    {
        $barang_id = $request->cart_barang_id;
        $input = $request->except('cart_barang_id');
        $input['barang_id'] = $barang_id;
        $input['user_id'] = Auth()->User()->id;
        CetakBarcode::create($input);
    }
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $cb = CetakBarcode::findorfail($id);
        $cb->update($input);
    }
    public function destroy($id)
    {
        CetakBarcode::destroy($id);
    }
    public function clearcetakbarcode()
    {
        // CetakBarcode::truncate();
        CetakBarcode::where('user_id', Auth()->User()->id)
            ->each(function ($query) {
                $query->delete();
            });
    }

    public function cetakdaripb($id) {}

    public function cetakbar1($break)
    {
        $cetakbar = Cetakbarcode::where('user_id', Auth()->User()->id)->get();
        return view('cetakbarcode.cetak1', compact(['cetakbar', 'break']));
    }
    public function cetakbarharga($break)
    {
        $cetakbar = Cetakbarcode::where('user_id', Auth()->User()->id)->get();
        return view('cetakbarcode.cetakharga', compact(['cetakbar', 'break']));
    }

    public function ceklistcetakharga(Request $request)
    {
        $id = $request->id;
        $input['cetak_harga'] = $request->cetak_harga;
        if ($id == 0) {
            CetakBarcode::each(function ($query) use ($input) {
                $query->update($input);
            });
        } else {
            $cb = CetakBarcode::find($id);
            if ($cb) {
                $cb->update($input);
            }
        }
        return response()->json(['success' => "Berhasil"]);
    }
}
