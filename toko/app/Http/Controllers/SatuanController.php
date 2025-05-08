<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Satuan;

class SatuanController extends Controller
{
    public function index()
    {
        return view('satuan.index');
    }
    public function TabelSatuan($deleted)
    {
        if ($deleted == 2) {        //trash
            $satuan = DB::table('satuan')
                ->where('deleted_at', '!=', null);
            return DataTables::of($satuan)
                ->addColumn('aksi', function ($satuan) {
                    return '<a onclick="RestoreTrash(' . $satuan->id . ')"  class="btn btn-success btn-xs"><i class="fas fa-pencil-alt"></i> RESTORE</a> ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        $satuan = DB::table('satuan')
            ->where('deleted_at', '=', null);
        return DataTables::of($satuan)
            ->addColumn('aksi', function ($satuan) {
                if (Auth()->User()->role_id == 1) {
                    return '<a onclick="OtEdit(' . $satuan->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ' .
                        '<a onclick="OtDelete(' . $satuan->id . ')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i></a>';
                }
                return '<a onclick="EditData(' . $satuan->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ' .
                    '<a onclick="DeleteData(' . $satuan->id . ')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i></a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_satuan'      => 'required|unique:satuan,nama_satuan,',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $input = $request->all();
        if ($request->kode == null) {
            $pel = Satuan::withTrashed()->latest('id')->first();
            if (empty($pel)) {
                $urut = 1;
            } else {
                $urut = $pel->id + 1;
            }
            $latest_id = 'PLG' . $urut;
            $input['kode'] = $latest_id;
        }

        if ($satuan = Satuan::create($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Satuan Berhasil Dibuat',
                'result' => $satuan
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Satuan Gagal Dibuat'
        ];
        return $data;
    }

    public function edit($id)
    {
        $Satuan = Satuan::findorfail($id);
        if (!$Satuan) {
            $data = [
                'success' => 0,
                'pesan' => 'Satuan Tidak ada'
            ];
            return $data;
        }
        if ($Satuan) {
            $Satuan['success'] = 1;
            return $Satuan;
        }
    }

    public function update(Request $request, $id)
    {
        $Satuan = Satuan::findOrFail($id);
        $input = $request->all();
        $validator = Validator::make($input, [
            'nama_satuan'    => 'required|unique:satuan,nama_satuan,' . $id,
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        if ($Satuan->update($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Satuan Berhasil Diubah'
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Satuan Tidak Berhasil Diubah'
        ];
        return $data;
    }

    public function destroy($id)
    {
        if (Satuan::destroy($id)) {
            $data = [
                'success' => 1,
                'pesan' => 'Satuan Berhasil Dihapus'
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Satuan Tidak Berhasil Dihapus'
        ];
        return $data;
    }
    public function Restore($id)
    {
        if (Satuan::withTrashed()->find($id)->restore()) {
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
