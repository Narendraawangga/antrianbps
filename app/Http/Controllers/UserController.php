<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan halaman manajemen pengguna
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL USER + PELAYANANNYA
        |--------------------------------------------------------------------------
        */

        $users = User::with('service')
            ->orderBy('created_at', 'desc')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK USER
        |--------------------------------------------------------------------------
        */

        $totalUsers = User::count();

        $totalAdmin = User::where(
            'role',
            'admin_utama'
        )->count();

        $totalPetugas = User::where(
            'role',
            'petugas'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | AMBIL PELAYANAN AKTIF
        |--------------------------------------------------------------------------
        |
        | Hanya pelayanan aktif yang akan muncul
        | pada dropdown Tambah Petugas.
        |
        */

        $services = Service::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN HALAMAN
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.users',
            compact(
                'users',
                'totalUsers',
                'totalAdmin',
                'totalPetugas',
                'services'
            )
        );
    }


    /**
     * Menambahkan pengguna
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

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

            /*
            |--------------------------------------------------------------------------
            | ROLE
            |--------------------------------------------------------------------------
            |
            | Hanya ada:
            |
            | admin_utama
            | petugas
            |
            */

            'role' => [
                'required',
                Rule::in([
                    'admin_utama',
                    'petugas',
                ]),
            ],

            /*
            |--------------------------------------------------------------------------
            | PELAYANAN
            |--------------------------------------------------------------------------
            |
            | service_id wajib jika role = petugas.
            |
            | Admin Utama tidak perlu pelayanan.
            |
            */

            'service_id' => [
                'nullable',
                'required_if:role,petugas',

                Rule::exists(
                    'services',
                    'id'
                )->where(
                    function ($query) {

                        $query->where(
                            'is_active',
                            true
                        );

                    }
                ),
            ],

        ], [

            /*
            |--------------------------------------------------------------------------
            | PESAN VALIDASI
            |--------------------------------------------------------------------------
            */

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

            'service_id.required_if' =>
                'Pelayanan wajib dipilih untuk petugas.',

            'service_id.exists' =>
                'Pelayanan yang dipilih tidak valid.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | TENTUKAN PELAYANAN
        |--------------------------------------------------------------------------
        |
        | Petugas:
        | service_id disimpan.
        |
        | Admin Utama:
        | service_id = NULL.
        |
        */

        $serviceId = null;

        if (
            $validated['role']
            === 'petugas'
        ) {

            $serviceId =
                $validated['service_id'];

        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN USER
        |--------------------------------------------------------------------------
        */

        User::create([

            'name' =>
                $validated['name'],

            'username' =>
                $validated['username'],

            'password' =>
                Hash::make(
                    $validated['password']
                ),

            'role' =>
                $validated['role'],

            'service_id' =>
                $serviceId,

            'is_active' =>
                true,

        ]);


        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE HALAMAN PENGGUNA
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                'Pengguna berhasil ditambahkan.'
            );
    }
}