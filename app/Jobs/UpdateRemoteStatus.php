<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\Remote\Remote;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateRemoteStatus implements ShouldQueue
{
    use Queueable, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Remote $remote)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (! $this->remote->works()) {
            return;
        }

        $this->remote->up();
    }
}
