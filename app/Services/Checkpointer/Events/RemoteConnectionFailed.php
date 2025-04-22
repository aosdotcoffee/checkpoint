<?php

declare(strict_types=1);

namespace App\Services\Checkpointer\Events;

use App\Services\Remote\Remote;
use Throwable;

/**
 * Dispatched when {@see \App\Services\Checkpointer\Fetcher} fails to establish
 * a connection to a remote
 */
final readonly class RemoteConnectionFailed
{
    public function __construct(
        public Remote $remote,
        public Throwable $error,
    ) { }
}
