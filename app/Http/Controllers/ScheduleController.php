<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Halaman jadwal petugas untuk admin
     */
    public function index()
    {
        $petugas = User::where('role', 'petugas')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

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
     * Simpan jadwal baru
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


        // Pastikan user adalah petugas aktif
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


        // Cek jadwal bentrok
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


    /**
     * Form edit jadwal
     */
    public function edit($id)
    {
        $schedule = Schedule::with('user')
            ->findOrFail($id);

        $petugas = User::where('role', 'petugas')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view(
            'admin.jadwal-edit',
            compact(
                'schedule',
                'petugas'
            )
        );
    }


    /**
     * Update jadwal
     */
    public function update(
        Request $request,
        $id
    ) {

        $schedule = Schedule::findOrFail($id);

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


        // Pastikan petugas aktif
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


        // Cek jadwal bentrok
        // Jadwal yang sedang diedit dikecualikan
        $bentrok = Schedule::where(
            'user_id',
            $validated['user_id']
        )
            ->where(
                'date',
                $validated['date']
            )
            ->where(
                'id',
                '!=',
                $schedule->id
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


        $schedule->update([

            'user_id' =>
            $validated['user_id'],

            'date' =>
            $validated['date'],

            'start_time' =>
            $validated['start_time'],

            'end_time' =>
            $validated['end_time'],

            'notes' =>
            $validated['notes'] ?? null,

        ]);


        return redirect()
            ->route('admin.jadwal')
            ->with(
                'success',
                'Jadwal petugas berhasil diperbarui.'
            );
    }


    /**
     * Menampilkan jadwal milik petugas yang sedang login
     */
    /**
     * Menampilkan jadwal milik petugas yang sedang login
     */
    public function petugasIndex()
    {
        $userId = Auth::id();

        $jadwals = Schedule::where('user_id', $userId)
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('petugas.jadwal', compact('jadwals'));
    }


    /**
     * Hapus jadwal
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);

        $schedule->delete();

        return redirect()
            ->route('admin.jadwal')
            ->with(
                'success',
                'Jadwal petugas berhasil dihapus.'
            );
    }
}
