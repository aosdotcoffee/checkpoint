<?php

namespace App\Providers;

use App\Models\Remote;
use App\Services\CheckpointerService;
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
            $remotes = Remote::query()->where('enabled', '=', true)->get();

            return new CheckpointerService(remotes: $remotes);
        });
    }
}
