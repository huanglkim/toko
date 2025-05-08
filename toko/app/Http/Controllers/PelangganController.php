<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Pelanggan;
use App\Imports\PelangganImport;
use App\Exports\ContohPelangganExport;
use App\Models\PelKendaraan;
use App\Models\Spk;
use App\Models\TransaksiOut;
use Maatwebsite\Excel\Facades\Excel;

class PelangganController extends Controller
{
    public function index()
    {
        return view('pelanggan.index');
    }
    public function TabelPelanggan($group, $status, $deleted)
    {
        $pelanggan = DB::table('pelanggan')
            ->where('status', $status)
            ->where(function ($query) use ($group) {
                if ($group != 0) {
                    $query->where('group', $group);
                }
            })
            ->where(function ($query) use ($deleted) {
                if ($deleted == 2) {
                    $query->where('deleted_at', '!=', null);
                } else {
                    $query->where('deleted_at', '=', null);
                }
            });
        return DataTables::of($pelanggan)
            ->addColumn('pilih', function ($pelanggan) {
                return '<input type="checkbox" class="sub_chk2" id="' .
                    $pelanggan->id .
                    '" data-id="' .
                    $pelanggan->id .
                    '">
                <label class="form-check-label" for="' .
                    $pelanggan->id .
                    '">
                                        PILIH PELANGGAN
                                    </label>
                ';
            })
            ->addColumn('aksi', function ($pelanggan) {
                $restore = '<a onclick="RestoreTrash(' . $pelanggan->id . ')"  class="btn btn-success btn-xs"><i class="fas fa-pencil-alt"></i> RESTORE</a> ';
                if ($pelanggan->deleted_at != null) {
                    return $restore;
                }
                if (Auth()->User()->role_id == 1) {
                    return '<a onclick="OtEdit(' . $pelanggan->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ' . '<a onclick="OtDelete(' . $pelanggan->id . ')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i> </a>';
                }
                return '<a onclick="EditData(' . $pelanggan->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ' . '<a onclick="DeleteData(' . $pelanggan->id . ')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i> </a>';
            })
            ->rawColumns(['pilih', 'aksi'])
            ->make(true);
    }
    public function show()
    {
        return Excel::download(new ContohPelangganExport(), 'pelanggan.xlsx');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'nullable|unique:pelanggan,kode',
            'nama' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'wa' => 'nullable|unique:pelanggan,wa',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $input = $request->all();
        $input['wa'] = $this->repairwa($request->wa);
        if ($request->kode == null) {
            $input['kode'] = kode(1);
        }

        if (Pelanggan::create($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Pelanggan Berhasil Dibuat',
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Pelanggan Gagal Dibuat',
        ];
        return $data;
    }

    public function edit($id)
    {
        $Pelanggan = Pelanggan::findorfail($id);
        if (!$Pelanggan) {
            $data = [
                'success' => 0,
                'pesan' => 'Pelanggan Tidak ada',
            ];
            return $data;
        }
        if ($Pelanggan) {
            $Pelanggan['success'] = 1;
            return $Pelanggan;
        }
    }

    public function update(Request $request, $id)
    {
        $Pelanggan = Pelanggan::findOrFail($id);
        $input = $request->all();
        $input['wa'] = $this->repairwa($request->wa);
        $validator = Validator::make($input, [
            'kode' => 'required|unique:pelanggan,kode,' . $id,
            'nama' => 'required|string',
            'alamat' => 'required',
            'kota' => 'required',
            //'wa'      => 'nullable|required|unique:pelanggan,wa,' . $id,
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        if ($Pelanggan->update($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Pelanggan Berhasil Diubah',
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Pelanggan Tidak Berhasil Diubah',
        ];
        return $data;
    }

    public function destroy($id)
    {
        if (Pelanggan::destroy($id)) {
            $data = [
                'success' => 1,
                'pesan' => 'Pelanggan Berhasil Dihapus',
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Pelanggan Tidak Berhasil Dihapus',
        ];
        return $data;
    }
    public function Restore($id)
    {
        if (Pelanggan::withTrashed()->find($id)->restore()) {
            $data = [
                'success' => 1,
                'pesan' => 'Kendaraan Berhasil DiRestore',
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Kendaraan Tidak Berhasil DiRestore',
        ];
        return $data;
    }
    public function caripelanggan(Request $request)
    {
        $cari = $request->caripelanggan;
        $data = DB::table('pelanggan')
            ->where('status', 1)
            ->where(function ($q) use ($cari) {
                $q->where('nama', 'LIKE', '%' . $cari . '%')
                    ->orwhere('kode', 'LIKE', '%' . $cari . '%')
                    ->orwhere('wa', 'LIKE', '%' . $cari . '%');
            })
            ->limit(20)
            ->get();
        return $data;
    }
    public function importpelanggan(Request $request)
    {
        // validasi
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx',
        ]);

        // menangkap file excel
        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = rand() . $file->getClientOriginalName();

        // upload ke folder  di dalam folder public
        $destinationPath = public_path('file_pelanggan');
        $file->move($destinationPath, $nama_file);

        // import data
        Excel::import(new PelangganImport(), public_path('/file_pelanggan/' . $nama_file));

        return redirect('/pelanggan')->with('sukses', 'Data Pelanggan Berhasil Diimport!');
    }
    public function Pilih($id)
    {
        $Pelanggan = Pelanggan::findorfail($id);
        if ($Pelanggan) {
            $data = [
                'success' => 1,
                'pelanggan' => $Pelanggan,
                'pesan' => 'pelanggan Berhasil DiRestore',
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'pelanggan Tidak Berhasil DiRestore',
        ];
        return $data;
    }

    public function margepelanggan(Request $request)
    {
        $ids = explode(',', $request->ids);
        $id_ok = $ids[0];
        $id_deleted = explode(',', $request->ids);
        array_shift($id_deleted);
        $delete_pel = Pelanggan::whereIn('id', $id_deleted)->get();
        foreach ($delete_pel as $dp) {
            $pelanggan_id = $dp->id;

            Pelanggan::destroy($dp->id);
        }
        $data = [
            'success' => 1,
            'pesan' => 'Pelanggan Berhasil DIMERGING',
        ];
        return $data;
    }
    public function repairwapelanggan()
    {
        $pelanggans = Pelanggan::all();
        foreach ($pelanggans as $pelanggan) {
            $wa = $pelanggan->wa;
            $nohp = preg_replace('/[^0-9]/', '', $wa);
            if (!preg_match('/[^+0-9]/', trim($nohp))) {
                // cek apakah no hp karakter ke 1 dan 2 adalah angka 62
                if (substr(trim($nohp), 0, 2) == '62') {
                    $hp = trim($nohp);
                }
                // cek apakah no hp karakter ke 1 adalah angka 0
                elseif (substr(trim($nohp), 0, 1) == '0') {
                    $hp = '62' . substr(trim($nohp), 1);
                }
            }
            $input['wa'] = $hp;
            $pelanggan->update($input);
        }
        $data = [
            'success' => 1,
            'pesan' => 'Pelanggan Berhasil DIMARGE',
        ];
        return $data;
    }
    public function repairwa($nohp)
    {
        $nohp = preg_replace('/[^0-9]/', '', $nohp);
        if ($nohp == '') {
            return '-';
        }
        if (!preg_match('/[^+0-9]/', trim($nohp))) {
            // cek apakah no hp karakter ke 1 dan 2 adalah angka 62
            $hp = $nohp;
            if (substr(trim($nohp), 0, 2) == '62') {
                $hp = trim($nohp);
            }
            // cek apakah no hp karakter ke 1 adalah angka 0
            elseif (substr(trim($nohp), 0, 1) == '0') {
                $hp = '62' . substr(trim($nohp), 1);
            }
        }
        return $hp;
    }
    public function fastupdatepel(Request $request, $id)
    {
        $Pelanggan = Pelanggan::findOrFail($id);
        $column = $request->column;
        $value = $request->value;
        if ($column == 'wa') {
            $value = $this->repairwa($value);
        }
        $input[$column] = $value;
        if ($Pelanggan->update($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Pelanggan Berhasil Diubah',
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Pelanggan Tidak Berhasil Diubah',
        ];
        return $data;
    }
}
