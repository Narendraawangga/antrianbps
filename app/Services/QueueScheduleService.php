<?php

namespace App\Services;

use Carbon\CarbonImmutable;

class QueueScheduleService
{
    public function now(): CarbonImmutable
    {
        return CarbonImmutable::now(
            config('antrian.timezone')
        );
    }


    public function isOpen(): bool
    {
        $now = $this->now();

        $open = $now->setTimeFromTimeString(
            config('antrian.open_time')
        );

        $close = $now->setTimeFromTimeString(
            config('antrian.close_time')
        );

        return $now->greaterThanOrEqualTo($open)
            && $now->lessThan($close);
    }


    public function periodStart(): CarbonImmutable
    {
        $now = $this->now();

        return $now->setTimeFromTimeString(
            config('antrian.open_time')
        );
    }


    public function periodEnd(): CarbonImmutable
    {
        return $this->periodStart()->setTimeFromTimeString(
            config('antrian.close_time')
        );
    }
}