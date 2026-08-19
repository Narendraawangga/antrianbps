<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Services\QueueScheduleService;

class DisplayController extends Controller
{
    private QueueScheduleService $schedule;

    public function __construct(QueueScheduleService $schedule)
    {
        $this->schedule = $schedule;
    }

    public function index()
    {
        $periodStart = $this->schedule
            ->periodStart()
            ->utc();

        $periodEnd = $this->schedule
            ->periodEnd()
            ->utc();

        $currentQueues = Queue::with('service')
            ->whereBetween(
                'created_at',
                [$periodStart, $periodEnd]
            )
            ->whereIn(
                'status',
                [
                    'called',
                    'serving',
                ]
            )
            ->orderByDesc('called_at')
            ->get();

        return view(
            'display.index',
            compact('currentQueues')
        );
    }

    /*
|--------------------------------------------------------------------------
| DATA ANTREAN UNTUK AUTO UPDATE DISPLAY
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

    $queues = Queue::with('service')
        ->whereBetween(
            'created_at',
            [$periodStart, $periodEnd]
        )
        ->whereIn(
            'status',
            [
                'called',
                'serving',
            ]
        )
        ->orderByDesc('called_at')
        ->get();


    return response()->json([
        'success' => true,

        'queues' => $queues->map(
            function ($queue) {

                return [
                    'id' => $queue->id,

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
    ]);
}
}