<?php

use App\Console\Commands\DispatchUpdateRemoteStatusJobs;
use Illuminate\Support\Facades\Schedule;

Schedule::command(DispatchUpdateRemoteStatusJobs::class)
    ->everyThirtySeconds();
