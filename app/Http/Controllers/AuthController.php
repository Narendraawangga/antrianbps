<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CEK USERNAME & PASSWORD
        |--------------------------------------------------------------------------
        */

        if (!Auth::attempt($credentials)) {

            return back()
                ->withErrors([
                    'username' =>
                    'Username atau password salah.'
                ])
                ->onlyInput('username');
        }


        /*
        |--------------------------------------------------------------------------
        | REGENERATE SESSION
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();


        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | CEK STATUS AKUN
        |--------------------------------------------------------------------------
        */

        if (!$user->is_active) {

            Auth::logout();

            return back()
                ->withErrors([
                    'username' =>
                    'Akun Anda tidak aktif. Silakan hubungi administrator.'
                ])
                ->onlyInput('username');
        }


        /*
        |--------------------------------------------------------------------------
        | REDIRECT BERDASARKAN ROLE
        |--------------------------------------------------------------------------
        */

       switch ($user->role) {
            case 'admin_utama':
                return redirect()->route('dashboard');

            case 'petugas':
                return redirect()->route('petugas.dashboard');

            default:
                Auth::logout();

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'username' => 'Role pengguna tidak dikenali.',
                    ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
        {
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()->route('login');
        }
}
