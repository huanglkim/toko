<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Suplier;

class SuplierController extends Controller
{
    public function carisuplier(Request $request)
    {
        $cari = $request->carisuplier;
        $data = DB::table('suplier')
            ->where('status', 1)
            ->where(function ($q) use ($cari) {
                $q->where('nama', 'LIKE', '%' . $cari . '%')
                    ->orwhere('kode', 'LIKE', '%' . $cari . '%');
            })
            ->limit(20)
            ->get();
        return $data;
    }
    public function index()
    {
        return view('suplier.index');
    }
    public function TabelSuplier($group, $status, $deleted)
    {
        $wheregroup = $group == 0 ? '!=' : '=';
        if ($deleted == 2) { //trash
            $suplier = DB::table('suplier')
                ->where('group', $wheregroup, $group)
                ->where('status', '=', $status)
                ->where('deleted_at', '!=', null);
            return DataTables::of($suplier)
                ->addColumn('aksi', function ($suplier) {
                    return '<a onclick="RestoreTrash(' . $suplier->id . ')"  class="btn btn-success btn-xs"><i class="fas fa-pencil-alt"></i> RESTORE</a> ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        if ($deleted == 1) {
            $suplier = DB::table('suplier')
                ->where('group', $wheregroup, $group)
                ->where('status', '=', $status)
                ->where('deleted_at', '=', null);
            return DataTables::of($suplier)
                ->addColumn('aksi', function ($suplier) {
                    if (Auth()->User()->role_id == 1) {
                        return '<a onclick="OtEdit(' . $suplier->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ' .
                            '<a onclick="OtDelete(' . $suplier->id . ')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i> </a>';
                    }
                    return '<a onclick="EditData(' . $suplier->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ' .
                        '<a onclick="DeleteData(' . $suplier->id . ')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i> </a>';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode'      => 'nullable|unique:suplier,kode',
            'nama'      => 'required',
            'alamat'    => 'required',
            'kota'      => 'required',
            'wa'        => 'nullable|unique:suplier,wa',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $input = $request->all();
        if ($request->kode == null) {
            $input['kode'] = kode(2);
        }

        if ($Suplier = Suplier::create($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Suplier Berhasil Dibuat',
                'id_sup' => $Suplier->id,
                'nama_sup' => $Suplier->nama . '/' . $Suplier->kode
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Suplier Gagal Dibuat'
        ];
        return $data;
    }

    public function edit($id)
    {
        $Suplier = Suplier::findorfail($id);
        if (!$Suplier) {
            $data = [
                'success' => 0,
                'pesan' => 'Suplier Tidak ada'
            ];
            return $data;
        }
        if ($Suplier) {
            $Suplier['success'] = 1;
            return $Suplier;
        }
    }

    public function update(Request $request, $id)
    {
        $Suplier = Suplier::findOrFail($id);
        $input = $request->all();
        $validator = Validator::make($input, [
            'kode'    => 'required|unique:suplier,kode,' . $id,
            'nama'    => 'required|string',
            'alamat'  => 'required',
            'kota'    => 'required',
            'wa'      => 'nullable|required|unique:suplier,wa,' . $id,
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        if ($Suplier->update($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Suplier Berhasil Diubah'
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Suplier Tidak Berhasil Diubah'
        ];
        return $data;
    }

    public function destroy($id)
    {
        if (Suplier::destroy($id)) {
            $data = [
                'success' => 1,
                'pesan' => 'Suplier Berhasil Dihapus'
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Suplier Tidak Berhasil Dihapus'
        ];
        return $data;
    }
    public function Restore($id)
    {
        if (Suplier::withTrashed()->find($id)->restore()) {
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
