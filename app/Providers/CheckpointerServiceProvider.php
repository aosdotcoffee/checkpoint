<?php

namespace App\Providers;

use App\Services\Checkpointer\Events\RemoteConnectionFailed;
use App\Services\CheckpointerService;
use App\Services\Remote\Remote;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class CheckpointerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CheckpointerService::class, function() {
            $remotes = Remote::active();

            return new CheckpointerService(remotes: $remotes);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen($this->onRemoteConnectionFailed(...));
    }

    /**
     * Listener for whenever a connection to a remote fails
     */
    private function onRemoteConnectionFailed(RemoteConnectionFailed $event)
    {
        $key = "onRemoteConnectionFailed#{$event->remote->getModel()->id}";

        Cache::lock($key, seconds: 5)->get(function() use (&$event) {
            // If this was updated by another process in the meantime, we need to refresh it
            if ($event->remote->refresh()->isDown()) {
                return;
            }

            $event->remote->down(
                reason: "{$event->error->getMessage()}\n\n{$event->error->getTraceAsString()}"
            );
        });
    }
}
