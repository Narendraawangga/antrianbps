<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Services\QueueScheduleService;

class DisplayController extends Controller
{
    private QueueScheduleService $schedule;

    public function __construct(
        QueueScheduleService $schedule
    ) {
        $this->schedule = $schedule;
    }


    /*
    |--------------------------------------------------------------------------
    | HALAMAN DISPLAY
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $periodStart = $this->schedule
            ->periodStart()
            ->utc();

        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();


        /*
        |--------------------------------------------------------------------------
        | ANTREAN AKTIF
        |--------------------------------------------------------------------------
        |
        | called  = sedang dipanggil
        | serving = sedang dilayani
        |
        */

        $currentQueues = Queue::with('service')

            ->whereBetween(
                'created_at',
                [
                    $periodStart,
                    $periodEnd
                ]
            )

            ->whereIn(
                'status',
                [
                    'called',
                    'serving',
                ]
            )

            // Yang sedang dipanggil ditampilkan terlebih dahulu
            ->orderByRaw(
                "CASE
                    WHEN status = 'called' THEN 0
                    WHEN status = 'serving' THEN 1
                    ELSE 2
                END"
            )

            ->orderByDesc('called_at')

            ->get();


        /*
        |--------------------------------------------------------------------------
        | ANTREAN BERIKUTNYA
        |--------------------------------------------------------------------------
        */

        $nextQueues = Queue::with('service')

            ->whereBetween(
                'created_at',
                [
                    $periodStart,
                    $periodEnd
                ]
            )

            ->where(
                'status',
                'waiting'
            )

            ->orderBy(
                'created_at',
                'asc'
            )

            ->limit(4)

            ->get();


        return view(
            'display.index',
            compact(
                'currentQueues',
                'nextQueues'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DATA AUTO UPDATE DISPLAY
    |--------------------------------------------------------------------------
    */

    public function data()
    {
        $periodStart = $this->schedule
            ->periodStart()
            ->utc();

        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();


        /*
        |--------------------------------------------------------------------------
        | ANTREAN AKTIF
        |--------------------------------------------------------------------------
        */

        $queues = Queue::with('service')

            ->whereBetween(
                'created_at',
                [
                    $periodStart,
                    $periodEnd
                ]
            )

            ->whereIn(
                'status',
                [
                    'called',
                    'serving',
                ]
            )

            ->orderByRaw(
                "CASE
                    WHEN status = 'called' THEN 0
                    WHEN status = 'serving' THEN 1
                    ELSE 2
                END"
            )

            ->orderByDesc('called_at')

            ->get();


        /*
        |--------------------------------------------------------------------------
        | ANTREAN MENUNGGU
        |--------------------------------------------------------------------------
        */

        $nextQueues = Queue::with('service')

            ->whereBetween(
                'created_at',
                [
                    $periodStart,
                    $periodEnd
                ]
            )

            ->where(
                'status',
                'waiting'
            )

            ->orderBy(
                'created_at',
                'asc'
            )

            ->limit(4)

            ->get();


        return response()->json([

            'success' => true,


            /*
            |--------------------------------------------------------------------------
            | DIPANGGIL + SEDANG DILAYANI
            |--------------------------------------------------------------------------
            */

            'queues' => $queues->map(
                function ($queue) {

                    return [

                        'id' =>
                            $queue->id,

                        'number' =>
                            $queue->queue_number,

                        'service' =>
                            $queue->service->name,

                        'status' =>
                            $queue->status,

                        'status_label' =>
                            $queue->status_label,

                        'called_at' =>
                            $queue->called_at,

                    ];
                }
            ),


            /*
            |--------------------------------------------------------------------------
            | ANTREAN BERIKUTNYA
            |--------------------------------------------------------------------------
            */

            'next_queues' => $nextQueues->map(
                function ($queue) {

                    return [

                        'id' =>
                            $queue->id,

                        'number' =>
                            $queue->queue_number,

                        'service' =>
                            $queue->service->name,

                        'status' =>
                            $queue->status,

                    ];
                }
            ),

        ]);
    }
}