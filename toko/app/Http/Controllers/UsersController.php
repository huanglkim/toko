<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    public function index()
    {
        $Users = Users::Active()->paginate(15);
        $Roles = Role::orderBy('nama_jabatan', 'ASC')->pluck('nama_jabatan', 'id');
        return view('users.index', compact(['Users', 'Roles']));
    }
    public function datausertabel(Request $request)
    {
        $cariuser = $request->cariuser;
        $status_user = $request->status_user;
        $orderby = $request->orderby;
        //  <option value="1">Tanggal Masuk A-Z</option>
        //                     <option value="2">Tanggal Masuk Z-A</option>
        //                     <option value="3">Nama A-Z</option>
        //                     <option value="4">Nama Z-A</option>
        if ($orderby == 1) {
            $ordercol = 'created_at';
            $tipeorder = 'DESC';
        }
        if ($orderby == 2) {
            $ordercol = 'created_at';
            $tipeorder = 'ASC';
        }
        if ($orderby == 3) {
            $ordercol = 'nama';
            $tipeorder = 'ASC';
        }
        if ($orderby == 4) {
            $ordercol = 'nama';
            $tipeorder = 'DESC';
        }
        $Users = Users::where('status', $status_user)
            ->orderBy($ordercol, $tipeorder)
            ->where('nama', 'LIKE', '%' . $cariuser . '%')
            ->paginate(16);
        $data = [
            'Users' => $Users,
        ];

        return view('users.datausertabel', $data)->render();
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'      => 'required|string',
            'username'  => 'required|unique:users,username',
            'password'  => 'required',
            'role_id'   => 'required',
            'wa'        => 'required|unique:users,wa',
            'rfid'      => 'nullable|unique:users,rfid',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $input = $request->all();
        $input['password'] = bcrypt($request->password);

        if (Users::create($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'User Berhasil Dibuat'
            ];
            return $data;
        };
    }

    public function edit($id)
    {
        $user = Users::find($id);
        if (!$user) {
            $data = [
                'success' => 0,
                'pesan' => 'User Tidak ada'
            ];
            return $data;
        }
        $user['success'] = 1;
        return $user;
    }


    public function update(Request $request, $id)
    {
        $user = Users::findOrFail($id);
        $input = $request->all();
        $validator = Validator::make($input, [
            'nama'      => 'required|string',
            'username'  => 'required|unique:users,username,' . $id,
            'role_id'   => 'required',
            'wa'        => 'required|unique:users,wa,' . $id,
            'rfid'      => 'nullable|unique:users,rfid,' . $id,
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }
        if ($request->password != null) {
            $input['password'] = bcrypt($request->password);
        } else {
            $input['password'] = $user->password;
        }

        if ($user->update($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'User Berhasil Diubah'
            ];
            return $data;
        };
    }

    public function destroy($id)
    {
        $user = Users::find($id);
        $user->update(['rfid' => null, 'wa' => null,]);
        if (Users::destroy($id)) {
            $data = [
                'success' => 1,
                'pesan' => 'User Berhasil Dihapus'
            ];
            return $data;
        } else {
            $data = [
                'success' => 0,
                'pesan' => 'User Tidak Berhasil Dihapus'
            ];
            return $data;
        };
    }
}
