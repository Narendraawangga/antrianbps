<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Menampilkan daftar layanan
     */
    public function index()
    {
        $services = Service::orderBy('name')->get();

        return view('admin.layanan', compact('services'));
    }


    /**
     * Menyimpan layanan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'required',
                'string',
                'max:20',
                'unique:services,code',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ], [
            'name.required' =>
            'Nama layanan wajib diisi.',

            'code.required' =>
            'Kode layanan wajib diisi.',

            'code.unique' =>
            'Kode layanan sudah digunakan.',

            'is_active.required' =>
            'Status layanan wajib dipilih.',
        ]);


        Service::create([
            'name' =>
            $validated['name'],

            'code' =>
            strtoupper($validated['code']),

            'description' =>
            $validated['description'] ?? null,

            'is_active' =>
            $validated['is_active'],
        ]);


        return redirect()
            ->route('admin.layanan')
            ->with(
                'success',
                'Layanan berhasil ditambahkan.'
            );
    }


    /**
     * Menampilkan halaman edit
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);

        return view(
            'admin.layanan-edit',
            compact('service')
        );
    }


    /**
     * Memperbarui layanan
     */
    public function update(
        Request $request,
        $id
    ) {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'required',
                'string',
                'max:2',
                'unique:services,code,' . $service->id,
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ], [
            'name.required' =>
            'Nama layanan wajib diisi.',

            'code.required' =>
            'Kode layanan wajib diisi.',

            'code.unique' =>
            'Kode layanan sudah digunakan.',
        ]);


        $service->update([
            'name' =>
            $validated['name'],

            'code' =>
            strtoupper($validated['code']),

            'description' =>
            $validated['description'] ?? null,

            'is_active' =>
            $validated['is_active'],
        ]);


        return redirect()
            ->route('admin.layanan')
            ->with(
                'success',
                'Layanan berhasil diperbarui.'
            );
    }


    /**
     * Menghapus layanan
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Cek apakah layanan sudah memiliki antrean
        |--------------------------------------------------------------------------
        */

        if ($service->queues()->exists()) {

            return redirect()
                ->route('admin.layanan')
                ->with(
                    'error',
                    'Layanan tidak dapat dihapus karena sudah memiliki data antrean.'
                );
        }


        $service->delete();


        return redirect()
            ->route('admin.layanan')
            ->with(
                'success',
                'Layanan berhasil dihapus.'
            );
    }
}
