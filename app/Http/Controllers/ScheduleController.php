<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Halaman jadwal petugas
     */
    public function index()
    {
        // Ambil hanya user yang ber-role petugas dan aktif
        $petugas = User::where('role', 'petugas')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        // Ambil jadwal beserta data petugas
        $schedules = Schedule::with('user')
            ->orderBy('date', 'desc')
            ->orderBy('start_time')
            ->get();

        return view('admin.jadwal', compact(
            'petugas',
            'schedules'
        ));
    }


    /**
     * Simpan jadwal
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'user_id' => [
                'required',
                'exists:users,id'
            ],

            'date' => [
                'required',
                'date'
            ],

            'start_time' => [
                'required',
                'date_format:H:i'
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time'
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000'
            ],

        ], [

            'user_id.required' =>
            'Petugas wajib dipilih.',

            'user_id.exists' =>
            'Petugas tidak ditemukan.',

            'date.required' =>
            'Tanggal wajib diisi.',

            'start_time.required' =>
            'Jam mulai wajib diisi.',

            'end_time.required' =>
            'Jam selesai wajib diisi.',

            'end_time.after' =>
            'Jam selesai harus lebih besar dari jam mulai.',

        ]);


        // Pastikan user memang petugas
        $petugas = User::where('id', $validated['user_id'])
            ->where('role', 'petugas')
            ->where('is_active', 1)
            ->first();

        if (!$petugas) {

            return back()
                ->withErrors([
                    'user_id' =>
                    'Petugas yang dipilih tidak valid.'
                ])
                ->withInput();
        }


        // Cegah jadwal bentrok untuk petugas yang sama
        $bentrok = Schedule::where(
            'user_id',
            $validated['user_id']
        )
            ->where(
                'date',
                $validated['date']
            )
            ->where(function ($query) use ($validated) {

                $query
                    ->where(
                        'start_time',
                        '<',
                        $validated['end_time']
                    )
                    ->where(
                        'end_time',
                        '>',
                        $validated['start_time']
                    );
            })
            ->exists();


        if ($bentrok) {

            return back()
                ->withErrors([
                    'date' =>
                    'Petugas tersebut sudah memiliki jadwal pada waktu tersebut.'
                ])
                ->withInput();
        }


        Schedule::create([

            'user_id' =>
            $validated['user_id'],

            'date' =>
            $validated['date'],

            'start_time' =>
            $validated['start_time'],

            'end_time' =>
            $validated['end_time'],

            'status' =>
            'aktif',

            'notes' =>
            $validated['notes'] ?? null,

        ]);


        return redirect()
            ->route('admin.jadwal')
            ->with(
                'success',
                'Jadwal petugas berhasil ditambahkan.'
            );
    }
}
