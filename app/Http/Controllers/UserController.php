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
        $users = User::with('service')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalUsers = User::count();

        $totalAdmin = User::where(
            'role',
            'admin_utama'
        )->count();

        $totalPetugas = User::where(
            'role',
            'petugas'
        )->count();

        $services = Service::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();

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
                Rule::in([
                    'admin_utama',
                    'petugas',
                ]),
            ],

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


        $serviceId = null;

        if (
            $validated['role'] === 'petugas'
        ) {

            $serviceId =
                $validated['service_id'];
        }


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


        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                'Pengguna berhasil ditambahkan.'
            );
    }


    /**
     * Menampilkan halaman edit pengguna
     */
    public function edit($id)
    {
        $user = User::with('service')
            ->findOrFail($id);

        $services = Service::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();

        return view(
            'admin.users-edit',
            compact(
                'user',
                'services'
            )
        );
    }


    /**
     * Memperbarui pengguna
     */
    public function update(
        Request $request,
        $id
    ) {

        $user = User::findOrFail($id);


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

                Rule::unique(
                    'users',
                    'username'
                )->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                Rule::in([
                    'admin_utama',
                    'petugas',
                ]),
            ],

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

            'name.required' =>
            'Nama lengkap wajib diisi.',

            'username.required' =>
            'Username wajib diisi.',

            'username.min' =>
            'Username minimal 4 karakter.',

            'username.unique' =>
            'Username sudah digunakan.',

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
        |--------------------------------------------------------------
        | TENTUKAN PELAYANAN
        |--------------------------------------------------------------
        */

        $serviceId = null;

        if (
            $validated['role'] === 'petugas'
        ) {

            $serviceId =
                $validated['service_id'];
        }


        /*
        |--------------------------------------------------------------
        | DATA YANG DIUPDATE
        |--------------------------------------------------------------
        */

        $data = [

            'name' =>
            $validated['name'],

            'username' =>
            $validated['username'],

            'role' =>
            $validated['role'],

            'service_id' =>
            $serviceId,

        ];


        /*
        |--------------------------------------------------------------
        | PASSWORD
        |--------------------------------------------------------------
        |
        | Password hanya diubah jika diisi.
        |
        */

        if (
            !empty($validated['password'])
        ) {

            $data['password'] =
                Hash::make(
                    $validated['password']
                );
        }


        $user->update($data);


        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                'Pengguna berhasil diperbarui.'
            );
    }


    /**
     * Menghapus pengguna
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);


        /*
        |--------------------------------------------------------------
        | CEGAH ADMIN MENGHAPUS DIRINYA SENDIRI
        |--------------------------------------------------------------
        */

        if (
            auth()->id() === $user->id
        ) {

            return redirect()
                ->route('admin.users')
                ->with(
                    'error',
                    'Anda tidak dapat menghapus akun sendiri.'
                );
        }


        /*
        |--------------------------------------------------------------
        | HAPUS USER
        |--------------------------------------------------------------
        */

        $user->delete();


        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                'Pengguna berhasil dihapus.'
            );
    }
}
