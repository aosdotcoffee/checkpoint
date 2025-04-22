<?php

namespace App\Console\Commands;

use App\Jobs\UpdateRemoteStatus;
use App\Services\Remote\Remote;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class DispatchUpdateRemoteStatusJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remote:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch jobs to update the status of dead remotes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Bus::batch([...$this->getJobs()])
            ->dispatch();
    }

    /**
     * Get an iterator of `UpdateRemoteStatus` jobs
     *
     * @return iterable<int, UpdateRemoteStatus>
     */
    private function getJobs()
    {
        foreach (Remote::dead() as $remote) {
            yield new UpdateRemoteStatus($remote);
        }
    }
}
