<?php

declare(strict_types=1);

namespace App\Services\Remote\Events;

use App\Services\Remote\Remote;

final readonly class RemoteDown
{
    public function __construct(public Remote $remote)
    {
        //
    }
}
