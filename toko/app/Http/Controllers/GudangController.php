<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Gudang;

class GudangController extends Controller
{
    public function index()
    {
        return view('gudang.index');
    }
    public function TabelGudang($deleted)
    {
        if ($deleted == 2) {        //trash
            $gudang = DB::table('gudang')
                ->where('deleted_at', '!=', null);
            return DataTables::of($gudang)
                ->addColumn('aksi', function ($gudang) {
                    return '<a onclick="RestoreTrash(' . $gudang->id . ')"  class="btn btn-success btn-xs"><i class="fas fa-pencil-alt"></i> RESTORE</a> ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        $gudang = DB::table('gudang')
            ->where('deleted_at', '=', null);
        return DataTables::of($gudang)
            ->addColumn('aksi', function ($gudang) {
                if (Auth()->User()->role_id == 1) {
                    return '<a onclick="OtEdit(' . $gudang->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i></a> ' .
                        '<a onclick="OtDelete(' . $gudang->id . ')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i></a>';
                }
                return '<a onclick="EditData(' . $gudang->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i></a> ' .
                    '<a onclick="DeleteData(' . $gudang->id . ')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i></a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'      => 'required|unique:gudang,nama,',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $input = $request->all();
        if ($request->kode == null) {
            $pel = Gudang::withTrashed()->latest('id')->first();
            if (empty($pel)) {
                $urut = 1;
            } else {
                $urut = $pel->id + 1;
            }
            $latest_id = 'PLG' . $urut;
            $input['kode'] = $latest_id;
        }

        if (Gudang::create($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Gudang Berhasil Dibuat'
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Gudang Gagal Dibuat'
        ];
        return $data;
    }

    public function edit($id)
    {
        $Gudang = Gudang::findorfail($id);
        if (!$Gudang) {
            $data = [
                'success' => 0,
                'pesan' => 'Gudang Tidak ada'
            ];
            return $data;
        }
        if ($Gudang) {
            $Gudang['success'] = 1;
            return $Gudang;
        }
    }

    public function update(Request $request, $id)
    {
        $Gudang = Gudang::findOrFail($id);
        $input = $request->all();
        $validator = Validator::make($input, [
            'nama'    => 'required|unique:gudang,nama,' . $id,
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        if ($Gudang->update($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Gudang Berhasil Diubah'
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Gudang Tidak Berhasil Diubah'
        ];
        return $data;
    }

    public function destroy($id)
    {
        if (Gudang::destroy($id)) {
            $data = [
                'success' => 1,
                'pesan' => 'Gudang Berhasil Dihapus'
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Gudang Tidak Berhasil Dihapus'
        ];
        return $data;
    }
    public function Restore($id)
    {
        if (Gudang::withTrashed()->find($id)->restore()) {
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
