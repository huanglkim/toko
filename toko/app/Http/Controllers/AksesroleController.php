<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Akses;
use App\Models\AksesJabatan;

class AksesroleController extends Controller
{
    public function RoleAkses()
    {
        $Role = Role::all();
        return view('menu.akses', compact(['Role']));
    }
    public function TabelAksesRole($id)
    {
        $Role = Role::findorfail($id);
        $AksesJabatan = AksesJabatan::where('role_id', $id)->get();
        $usm = $AksesJabatan->pluck('akses_id');

        if ($usm != []) {
            $NonAksesJabatan = Akses::whereNotIn('id', $usm)->orderBY('induk', 'DESC')->get();
        } else {
            $NonAksesJabatan = Akses::orderBY('induk', 'DESC')->get();
        }

        $data = [
            'Role' => $Role,
            'AksesRoles' => $AksesJabatan,
            'NonAksesRoles' => $NonAksesJabatan,
        ];

        return view('menu.tabelaksesrole', $data);
    }
    public function TambahAkses(Request $request)
    {
        $role_id = $request->role_id;
        $akses_id = $request->akses_id;
        $akses = AksesJabatan::where('role_id', $role_id)->where('Akses_id', $akses_id)->first();
        if (empty($akses)) {
            $input = $request->all();
            AksesJabatan::create($input);
            echo json_encode('Akses Berhasil ditambah');
        } else {
            echo json_encode('Akses Sudah ditambah');
        }
    }
    public function HapusAkses($id)
    {
        AksesJabatan::destroy($id);
        echo json_encode('Akses Berhasil dihapus');
    }
}
