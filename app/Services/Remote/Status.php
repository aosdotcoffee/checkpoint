<?php

declare(strict_types=1);

namespace App\Services\Remote;

enum Status: string
{
    case Up = 'UP';
    case Down = 'DOWN';
}
