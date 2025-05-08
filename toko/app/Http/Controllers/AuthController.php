<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::id() == null) {
            return view('login.index');
        }
        return redirect('/dashadmin');
        // if (Auth()->user()->role_id == 1) {
        // }
        // if (Auth()->user()->role_id == 3) {
        //     return redirect('/dashkasir');
        // }
        // return redirect('/dashkaryawan');
    }
    public function loginrfid()
    {
        if (Auth::id() == null) {
            return view('login.rfid');
        }
        return redirect('/dashadmin');
        // if (Auth()->user()->role_id == 1) {
        // }
        // if (Auth()->user()->role_id == 3) {
        //     return redirect('/dashkasir');
        // }
        // return redirect('/dashkaryawan');
    }
    public function postlogin(Request $request)
    {
        if ($request->password == null) {
            return redirect('/')->withErrors(['Masukkan Password']);
        } else {
            if (Auth::attempt($request->only('username', 'password'))) {
                if (Auth()->user()->status == 0) {
                    Auth::logout();
                    return redirect('/')->withErrors(['User Tidak Aktif']);
                }
                return redirect('/');
            } else {
                return redirect('/')->withErrors(['Username/Password Salah']);
            }
        }
    }
    public function postloginrfid(Request $request)
    {
        $rfid = $request->rfid;
        $user = Users::where('rfid', $rfid)->first();
        if (empty($s)) {
            return redirect('/loginrfid')->withErrors(['RFID Tidak Terdafatar']);
        } else {
            Auth::loginUsingId($user->id);
            return redirect('/');
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
