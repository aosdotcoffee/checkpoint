<?php

declare(strict_types=1);

namespace App\Services\Remote;

use App\Models;
use Illuminate\Database\Eloquent;

final readonly class Remote
{
    /**
     * Get all active remotes
     *
     * @return Eloquent\Collection<int, Models\Remote>
     */
    public static function active()
    {
        return Models\Remote::query()
            ->enabled()
            ->get();
    }

    public function __construct(private Models\Remote $model)
    {
        //
    }

    /**
     * Get the model instance
     */
    public function getModel()
    {
        return $this->model;
    }
}
