<?php

use App\Console\Commands\DispatchUpdateRemoteStatusJobs;
use App\Services\Remote\Remote;
use Illuminate\Support\Facades\Schedule;

Schedule::command(DispatchUpdateRemoteStatusJobs::class)
    ->everyThirtySeconds()
    ->when(fn () => count(Remote::dead()) > 0);
