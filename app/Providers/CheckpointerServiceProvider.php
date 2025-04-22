<?php

namespace App\Providers;

use App\Services\CheckpointerService;
use App\Services\Remote\Remote;
use Illuminate\Support\ServiceProvider;

class CheckpointerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->singleton(CheckpointerService::class, function() {
            $remotes = Remote::active();

            return new CheckpointerService(remotes: $remotes);
        });
    }
}
