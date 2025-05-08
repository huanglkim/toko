<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menus;
use App\Models\MenuRoles;
use App\Models\Role;
use App\Models\Menufav;

class MenuController extends Controller
{

    public function create()
    {
        $menus = Menus::orderBy('induk', 'ASC')->get();
        return view('menu.tambahmenu', compact(['menus']));
    }
    public function tambaheditmenu(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            $input = $request->all();
            Menus::create($input);
            return 1;
        }
        $menu = Menus::findorfail($id);
        $input = $request->all();
        $menu->update($input);
        return 1;
    }

    public function edit($id)
    {
        $menu = Menus::findorfail($id);
        return $menu;
    }
    public function RoleMenu()
    {
        $Role = Role::orderBy('nama_jabatan', 'ASC')->get();
        return view('menu.menu', compact(['Role']));
    }
    public function TabelMenuRole($id)
    {
        $Role = Role::findorfail($id);
        $MenuRoles = MenuRoles::where('role_id', $id)->get();
        $usm = $MenuRoles->pluck('menu_id');

        if ($usm != []) {
            $NonMenuRoles = Menus::whereNotIn('id', $usm)->orderBY('induk', 'DESC')->get();
        } else {
            $NonMenuRoles = Menus::orderBY('induk', 'DESC')->get();
        }

        $data = [
            'Role' => $Role,
            'MenuRoles' => $MenuRoles,
            'NonMenuRoles' => $NonMenuRoles,
        ];

        return view('menu.tabelmenurole', $data);
    }
    public function TambahMenu(Request $request)
    {
        $role_id = $request->role_id;
        $menu_id = $request->menu_id;
        $menu = MenuRoles::where('role_id', $role_id)->where('menu_id', $menu_id)->first();
        if (empty($menu)) {
            $input = $request->all();
            MenuRoles::create($input);
            echo json_encode('Menu Berhasil ditambah');
        } else {
            echo json_encode('Menu Sudah ditambah');
        }
    }
    public function HapusMenu($id)
    {
        MenuRoles::withTrashed()->where('id', $id)->forceDelete();
        echo json_encode('Menu Berhasil dihapus');
    }
    public function menufav(Request $request)
    {
        $user_id = Auth()->User()->id;
        $menu_id = $request->menu_id;
        $menufav_id = $request->menufav_id;
        if ($menufav_id == 0) {
            $menufav = Menufav::where('user_id', $user_id)->where('menu_id', $menu_id)->first();
            if ($menufav) {
                $data = [
                    'success' => 0,
                    'pesan' => 'sudah fav'
                ];
                return $data;
            }
            $input['user_id'] = $user_id;
            $input['menu_id'] = $menu_id;
            Menufav::create($input);
            $data = [
                'success' => 1,
                'pesan' => 'OK'
            ];
            return $data;
        } else {
            Menufav::destroy($menufav_id);
            $data = [
                'success' => 1,
                'pesan' => 'OK'
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'ERROR'
        ];
        return $data;
    }

    public function favunfav($id)
    {
        $menu = Menus::find($id);
        if ($menu->fav == 0) {
            $input['fav'] = 1;
        } else {
            $input['fav'] = 0;
        }
        $menu->update($input);
        $data = [
            'success' => 1,
            'pesan' => 'OK'
        ];
        return $data;
    }
}
