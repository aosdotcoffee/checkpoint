<?php

declare(strict_types=1);

namespace App\Services\Remote;

use App\Models;
use App\Services\Remote\Events\RemoteDown;
use App\Services\Remote\Events\RemoteUp;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

final readonly class Remote
{
    /**
     * Get all "active" remotes (enabled, up)
     *
     * @return array<int, static>
     */
    public static function active()
    {
        return Models\Remote::query()
            ->enabled()
            ->status(Status::Up)
            ->get()
            ->map(fn ($model) => new static($model))
            ->toArray();
    }

    /**
     * Get all "dead" remotes (enabled, down)
     *
     * @return array<int, static>
     */
    public static function dead()
    {
        return Models\Remote::query()
            ->enabled()
            ->status(Status::Down)
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

    /**
     * Returns true if the remote is marked as down
     */
    public function isDown()
    {
        return $this->model->status === Status::Down;
    }

    /**
     * Mark the remote as being up
     */
    public function up()
    {
        $this->model->update([
            'status' => Status::Up,
            'message' => null,
        ]);

        Event::dispatch(new RemoteUp(remote: $this));

        return $this;
    }

    /**
     * Mark the remote as being down
     */
    public function down(string $reason)
    {
        $this->model->update([
            'status' => Status::Down,
            'message' => $reason,
        ]);

        Event::dispatch(new RemoteDown(remote: $this));

        return $this;
    }

    /**
     * Test if the remote responds
     */
    public function works()
    {
        try {
            return Http::createPendingRequest()
                ->timeout(3)
                ->get($this->getUrl())
                ->successful();
        } catch (ConnectionException) {
            return false;
        }
    }

    /**
     * Get the remote name, formatted as `'{$shortName}' ({$longName})`
     */
    public function getFullName()
    {
        return "'{$this->model->short_name}' ({$this->model->name})";
    }
}
