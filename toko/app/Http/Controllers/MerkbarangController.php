<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Merkbarang;

class MerkbarangController extends Controller
{
    public function index()
    {
        return view('merkbarang.index');
    }
    public function TabelMerkbarang($deleted)
    {
        if ($deleted == 2) {        //trash
            $merkbarang = DB::table('merkbarang')
                ->where('deleted_at', '!=', null);
            return DataTables::of($merkbarang)
                ->addColumn('aksi', function ($merkbarang) {
                    return '<a onclick="RestoreTrash(' . $merkbarang->id . ')"  class="btn btn-success btn-xs"><i class="fas fa-pencil-alt"></i> RESTORE</a> ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        $merkbarang = DB::table('merkbarang')
            ->where('deleted_at', '=', null);
        return DataTables::of($merkbarang)
            ->addColumn('aksi', function ($merkbarang) {
                if (Auth()->User()->role_id == 1) {
                    return '<a onclick="OtEdit(' . $merkbarang->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ' .
                        '<a onclick="OtDelete(' . $merkbarang->id . ')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i> </a>';
                }
                return '<a onclick="EditData(' . $merkbarang->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ' .
                    '<a onclick="DeleteData(' . $merkbarang->id . ')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i> </a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_merkbarang'      => 'required|unique:merkbarang,nama_merkbarang,',
        ], [
            'nama_merkbarang.required' => 'Nama Merk Barang harus diisi.', // Custom message for "required"
            'nama_merkbarang.unique'   => 'Nama Merk Barang sudah terdaftar.', // Custom message for "unique"
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $input = $request->all();
        if ($request->kode == null) {
            $pel = Merkbarang::withTrashed()->latest('id')->first();
            if (empty($pel)) {
                $urut = 1;
            } else {
                $urut = $pel->id + 1;
            }
            $latest_id = 'PLG' . $urut;
            $input['kode'] = $latest_id;
        }

        if ($merkbarang = Merkbarang::create($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Merkbarang Berhasil Dibuat',
                'result' => $merkbarang,
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Merkbarang Gagal Dibuat'
        ];
        return $data;
    }

    public function edit($id)
    {
        $Merkbarang = Merkbarang::findorfail($id);
        if (!$Merkbarang) {
            $data = [
                'success' => 0,
                'pesan' => 'Merkbarang Tidak ada'
            ];
            return $data;
        }
        if ($Merkbarang) {
            $Merkbarang['success'] = 1;
            return $Merkbarang;
        }
    }

    public function update(Request $request, $id)
    {
        $Merkbarang = Merkbarang::findOrFail($id);
        $input = $request->all();
        $validator = Validator::make($input, [
            'nama_merkbarang'    => 'required|unique:merkbarang,nama_merkbarang,' . $id,
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        if ($Merkbarang->update($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Merkbarang Berhasil Diubah'
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Merkbarang Tidak Berhasil Diubah'
        ];
        return $data;
    }

    public function destroy($id)
    {
        if (Merkbarang::destroy($id)) {
            $data = [
                'success' => 1,
                'pesan' => 'Merkbarang Berhasil Dihapus'
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Merkbarang Tidak Berhasil Dihapus'
        ];
        return $data;
    }
    public function Restore($id)
    {
        if (Merkbarang::withTrashed()->find($id)->restore()) {
            $data = [
                'success' => 1,
                'pesan' => 'Kendaraan Berhasil DiRestore'
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Kendaraan Tidak Berhasil DiRestore'
        ];
        return $data;
    }
}
