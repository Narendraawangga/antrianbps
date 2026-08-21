<?php

namespace App\Http\Controllers;

use App\Models\PpidGuest;
use Illuminate\Http\Request;

class PpidController extends Controller
{
    /**
     * Menampilkan Form Tamu PPID
     */
    public function create()
    {
        return view('ppid.form');
    }


    /**
     * Menyimpan data Tamu PPID
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'tanggal' => [
                    'required',
                    'date',
                ],

                'nama' => [
                    'required',
                    'string',
                    'min:3',
                    'max:255',
                ],

                'whatsapp' => [
                    'required',
                    'string',
                    'max:20',
                    'regex:/^08[0-9]{8,18}$/',
                ],

                'pekerjaan' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'alamat' => [
                    'required',
                    'string',
                    'min:5',
                ],

                'asal_instansi' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255',
                ],

                'tujuan' => [
                    'required',
                    'string',
                    'min:5',
                ],
            ],

            [
                'tanggal.required' =>
                'Tanggal wajib diisi.',

                'nama.required' =>
                'Nama wajib diisi.',

                'nama.min' =>
                'Nama minimal 3 karakter.',

                'whatsapp.required' =>
                'Nomor WhatsApp wajib diisi.',

                'whatsapp.regex' =>
                'Nomor WhatsApp harus diawali 08 dan menggunakan angka.',

                'pekerjaan.required' =>
                'Pekerjaan wajib diisi.',

                'alamat.required' =>
                'Alamat wajib diisi.',

                'alamat.min' =>
                'Alamat minimal 5 karakter.',

                'asal_instansi.required' =>
                'Asal instansi wajib diisi.',

                'tujuan.required' =>
                'Tujuan wajib diisi.',

                'tujuan.min' =>
                'Tujuan minimal 5 karakter.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | SIMPAN KE DATABASE
        |--------------------------------------------------------------------------
        */

        PpidGuest::create($validated);


        /*
        |--------------------------------------------------------------------------
        | KEMBALI DENGAN PESAN BERHASIL
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('ppid.form')
            ->with(
                'success',
                'Data tamu PPID berhasil disimpan.'
            );
    }
    /**
     * Menampilkan daftar tamu PPID untuk Admin
     */
    /**
     * Menampilkan daftar tamu PPID untuk Admin
     */
    public function indexAdmin()
    {
        $guests = PpidGuest::latest('tanggal')
            ->latest('id')
            ->paginate(10);

        return view('admin.ppid', compact('guests'));
    }

    /**
     * Memanggil tamu PPID
     */
    public function panggil($id)
    {
        $guest = PpidGuest::findOrFail($id);

        $guest->update([
            'status' => 'dipanggil',
            'called_at' => now(),
            'completed_at' => null,
        ]);

        return redirect()
            ->route('admin.ppid')
            ->with('success', 'Tamu berhasil dipanggil.');
    }

    /**
     * Menyelesaikan pelayanan tamu PPID
     */
    public function selesai($id)
    {
        $guest = PpidGuest::findOrFail($id);

        $guest->update([
            'status' => 'selesai',
            'completed_at' => now(),
        ]);

        return redirect()
            ->route('admin.ppid')
            ->with('success', 'Pelayanan tamu berhasil diselesaikan.');
    }
    /**
     * Menghapus data tamu PPID
     */
    public function destroyAdmin($id)
    {
        $guest = PpidGuest::findOrFail($id);

        $guest->delete();

        return redirect()
            ->route('admin.ppid')
            ->with('success', 'Data tamu berhasil dihapus.');
    }
}
