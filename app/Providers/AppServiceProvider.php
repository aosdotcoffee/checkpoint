<?php

namespace App\Providers;

use App\Policies\RolePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, RolePolicy::class);
        Gate::before(fn ($user, $ability) => $user->hasRole('superadmin') ? true: null);
        // Gate::before(fn ($user, $ability) => true);
    }
}
