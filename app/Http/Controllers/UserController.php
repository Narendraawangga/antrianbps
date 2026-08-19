<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Menampilkan halaman manajemen pengguna
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        $totalUsers = User::count();

        $totalAdmin = User::whereIn('role', [
            'admin',
            'admin_utama'
        ])->count();

        $totalPetugas = User::where('role', 'petugas')->count();

        return view('admin.users', compact(
            'users',
            'totalUsers',
            'totalAdmin',
            'totalPetugas'
        ));
    }


    /**
     * Menambahkan pengguna
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'username' => [
                'required',
                'string',
                'min:4',
                'max:30',
                'unique:users,username',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                'in:admin,petugas',
            ],

        ], [

            'name.required' =>
            'Nama lengkap wajib diisi.',

            'username.required' =>
            'Username wajib diisi.',

            'username.min' =>
            'Username minimal 4 karakter.',

            'username.unique' =>
            'Username sudah digunakan.',

            'password.required' =>
            'Password wajib diisi.',

            'password.min' =>
            'Password minimal 8 karakter.',

            'password.confirmed' =>
            'Konfirmasi password tidak sama.',

            'role.required' =>
            'Role wajib dipilih.',

            'role.in' =>
            'Role tidak valid.',
        ]);


        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'is_active' => true,
        ]);


        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                'Pengguna berhasil ditambahkan.'
            );
    }
}
