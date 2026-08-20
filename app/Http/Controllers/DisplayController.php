<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Service;
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
        | PELAYANAN AKTIF
        |--------------------------------------------------------------------------
        |
        | Hanya 3 pelayanan aktif yang ditampilkan.
        | Penjualan Produk Statistik tidak ikut karena is_active = false.
        |
        */

        $services = Service::where(
            'is_active',
            true
        )
            ->orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | DATA PER PELAYANAN
        |--------------------------------------------------------------------------
        */

        $displayServices = $services->map(
            function ($service) use (
                $periodStart,
                $periodEnd
            ) {

                /*
                |--------------------------------------------------------------------------
                | ANTREAN AKTIF PELAYANAN INI
                |--------------------------------------------------------------------------
                */

                $currentQueue = Queue::with('service')

                    ->whereBetween(
                        'created_at',
                        [
                            $periodStart,
                            $periodEnd
                        ]
                    )

                    ->where(
                        'service_id',
                        $service->id
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

                    ->first();


                /*
                |--------------------------------------------------------------------------
                | ANTREAN BERIKUTNYA PELAYANAN INI
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
                        'service_id',
                        $service->id
                    )

                    ->where(
                        'status',
                        'waiting'
                    )

                    ->orderBy(
                        'created_at',
                        'asc'
                    )

                    ->limit(3)

                    ->get();


                return [

                    'service' =>
                        $service,

                    'current_queue' =>
                        $currentQueue,

                    'next_queues' =>
                        $nextQueues,

                ];
            }
        );


        /*
        |--------------------------------------------------------------------------
        | DATA LAMA
        |--------------------------------------------------------------------------
        |
        | Untuk sementara masih dikirim supaya Blade lama
        | tidak langsung error sebelum kita ubah.
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

            ->orderByRaw(
                "CASE
                    WHEN status = 'called' THEN 0
                    WHEN status = 'serving' THEN 1
                    ELSE 2
                END"
            )

            ->orderByDesc('called_at')

            ->get();


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


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN HALAMAN
        |--------------------------------------------------------------------------
        */

        return view(
            'display.index',
            compact(
                'displayServices',
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
        | PELAYANAN AKTIF
        |--------------------------------------------------------------------------
        */

        $services = Service::where(
            'is_active',
            true
        )
            ->orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | DATA SETIAP PELAYANAN
        |--------------------------------------------------------------------------
        */

        $serviceData = $services->map(
            function ($service) use (
                $periodStart,
                $periodEnd
            ) {

                /*
                |--------------------------------------------------------------------------
                | ANTREAN AKTIF
                |--------------------------------------------------------------------------
                */

                $currentQueue = Queue::whereBetween(
                    'created_at',
                    [
                        $periodStart,
                        $periodEnd
                    ]
                )

                    ->where(
                        'service_id',
                        $service->id
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

                    ->orderByDesc(
                        'called_at'
                    )

                    ->first();


                /*
                |--------------------------------------------------------------------------
                | ANTREAN MENUNGGU
                |--------------------------------------------------------------------------
                */

                $nextQueues = Queue::whereBetween(
                    'created_at',
                    [
                        $periodStart,
                        $periodEnd
                    ]
                )

                    ->where(
                        'service_id',
                        $service->id
                    )

                    ->where(
                        'status',
                        'waiting'
                    )

                    ->orderBy(
                        'created_at',
                        'asc'
                    )

                    ->limit(3)

                    ->get();


                /*
                |--------------------------------------------------------------------------
                | FORMAT JSON
                |--------------------------------------------------------------------------
                */

                return [

                    'service_id' =>
                        $service->id,

                    'service_code' =>
                        $service->code,

                    'service_name' =>
                        $service->name,


                    /*
                    |--------------------------------------------------------------------------
                    | ANTREAN AKTIF
                    |--------------------------------------------------------------------------
                    */

                    'current_queue' =>
                        $currentQueue
                            ? [

                                'id' =>
                                    $currentQueue->id,

                                'number' =>
                                    $currentQueue->queue_number,

                                'status' =>
                                    $currentQueue->status,

                                'status_label' =>
                                    $currentQueue->status_label,

                                'called_at' =>
                                    $currentQueue->called_at,

                            ]
                            : null,


                    /*
                    |--------------------------------------------------------------------------
                    | ANTREAN BERIKUTNYA
                    |--------------------------------------------------------------------------
                    */

                    'next_queues' =>
                        $nextQueues->map(
                            function ($queue) {

                                return [

                                    'id' =>
                                        $queue->id,

                                    'number' =>
                                        $queue->queue_number,

                                    'status' =>
                                        $queue->status,

                                ];
                            }
                        ),

                ];
            }
        );


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' =>
                true,

            'services' =>
                $serviceData,

        ]);
    }
}