<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Globalconfig;

class GlobalconfigController extends Controller
{
    public function index()
    {
        return view('globalconfig.index');
    }
    public function TabelGlobalconfig($deleted)
    {

        $globalconfig = DB::table('globalconfig')
            ->where(function ($q) use ($deleted) {
                if ($deleted == 2) {
                    $q->where('deleted_at', '!=', null);
                } else {
                    $q->where('deleted_at', '=', null);
                }
            });
        return DataTables::of($globalconfig)
            ->addColumn('aksi', function ($globalconfig) {
                if ($globalconfig->deleted_at != null) {
                    return '<a onclick="RestoreTrash(' . $globalconfig->id . ')"  class="btn btn-success btn-xs"><i class="fas fa-pencil-alt"></i> RESTORE</a> ';
                }
                if (Auth()->User()->role_id == 1) {
                    return '<a onclick="OtEdit(' . $globalconfig->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ' .
                        '<a onclick="OtDelete(' . $globalconfig->id . ')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i> </a>';
                }
                return '<a onclick="EditData(' . $globalconfig->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ' .
                    '<a onclick="DeleteData(' . $globalconfig->id . ')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i> </a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_config'      => 'required|unique:globalconfig,nama_config,',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $input = $request->all();

        if ($globalconfig = Globalconfig::create($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Globalconfig Berhasil Dibuat',
                'result' => $globalconfig
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Globalconfig Gagal Dibuat'
        ];
        return $data;
    }

    public function edit($id)
    {
        $Globalconfig = Globalconfig::findorfail($id);
        if (!$Globalconfig) {
            $data = [
                'success' => 0,
                'pesan' => 'Globalconfig Tidak ada'
            ];
            return $data;
        }
        if ($Globalconfig) {
            $Globalconfig['success'] = 1;
            return $Globalconfig;
        }
    }

    public function update(Request $request, $id)
    {
        $Globalconfig = Globalconfig::findOrFail($id);
        $input = $request->all();
        $validator = Validator::make($input, [
            'nama_config'    => 'required|unique:globalconfig,nama_config,' . $id,
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        if ($Globalconfig->update($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Globalconfig Berhasil Diubah'
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Globalconfig Tidak Berhasil Diubah'
        ];
        return $data;
    }

    public function destroy($id)
    {
        if (Globalconfig::destroy($id)) {
            $data = [
                'success' => 1,
                'pesan' => 'Globalconfig Berhasil Dihapus'
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Globalconfig Tidak Berhasil Dihapus'
        ];
        return $data;
    }
    public function Restore($id)
    {
        if (Globalconfig::withTrashed()->find($id)->restore()) {
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
