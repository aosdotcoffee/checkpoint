<?php

declare(strict_types=1);

namespace App\Services\Remote;

use App\Models;

final readonly class Remote
{
    /**
     * Get all active remotes
     *
     * @return array<int, static>
     */
    public static function active()
    {
        return Models\Remote::query()
            ->enabled()
            ->get()
            ->map(fn ($model) => new static($model))
            ->toArray();
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

    /**
     * Get the remote's URL
     */
    public function getUrl()
    {
        return $this->model->url;
    }
}
