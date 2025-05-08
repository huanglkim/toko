<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Jenisbarang;

class JenisbarangController extends Controller
{
    public function index()
    {
        return view('jenisbarang.index');
    }
    public function TabelJenisbarang($deleted)
    {
        if ($deleted == 2) {        //trash
            $jenisbarang = DB::table('jenisbarang')
                ->where('deleted_at', '!=', null);
            return DataTables::of($jenisbarang)
                ->addColumn('aksi', function ($jenisbarang) {
                    return '<a onclick="RestoreTrash(' . $jenisbarang->id . ')"  class="btn btn-success btn-xs"><i class="fas fa-pencil-alt"></i> RESTORE</a> ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        $jenisbarang = DB::table('jenisbarang')
            ->where('deleted_at', '=', null);
        return DataTables::of($jenisbarang)
            ->addColumn('aksi', function ($jenisbarang) {
                if (Auth()->User()->role_id == 1) {
                    return '<a onclick="OtEdit(' . $jenisbarang->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ' .
                        '<a onclick="OtDelete(' . $jenisbarang->id . ')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i> </a>';
                }
                return '<a onclick="EditData(' . $jenisbarang->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ' .
                    '<a onclick="DeleteData(' . $jenisbarang->id . ')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i> </a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jenisbarang'      => 'required|unique:jenisbarang,nama_jenisbarang,',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $input = $request->all();
        if ($request->kode == null) {
            $pel = Jenisbarang::withTrashed()->latest('id')->first();
            if (empty($pel)) {
                $urut = 1;
            } else {
                $urut = $pel->id + 1;
            }
            $latest_id = 'PLG' . $urut;
            $input['kode'] = $latest_id;
        }

        if ($jenisbarang = Jenisbarang::create($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Jenisbarang Berhasil Dibuat',
                'result' => $jenisbarang
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Jenisbarang Gagal Dibuat'
        ];
        return $data;
    }

    public function edit($id)
    {
        $Jenisbarang = Jenisbarang::findorfail($id);
        if (!$Jenisbarang) {
            $data = [
                'success' => 0,
                'pesan' => 'Jenisbarang Tidak ada'
            ];
            return $data;
        }
        if ($Jenisbarang) {
            $Jenisbarang['success'] = 1;
            return $Jenisbarang;
        }
    }

    public function update(Request $request, $id)
    {
        $Jenisbarang = Jenisbarang::findOrFail($id);
        $input = $request->all();
        $validator = Validator::make($input, [
            'nama_jenisbarang'    => 'required|unique:jenisbarang,nama_jenisbarang,' . $id,
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        if ($Jenisbarang->update($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Jenisbarang Berhasil Diubah'
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Jenisbarang Tidak Berhasil Diubah'
        ];
        return $data;
    }

    public function destroy($id)
    {
        if (Jenisbarang::destroy($id)) {
            $data = [
                'success' => 1,
                'pesan' => 'Jenisbarang Berhasil Dihapus'
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Jenisbarang Tidak Berhasil Dihapus'
        ];
        return $data;
    }
    public function Restore($id)
    {
        if (Jenisbarang::withTrashed()->find($id)->restore()) {
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
