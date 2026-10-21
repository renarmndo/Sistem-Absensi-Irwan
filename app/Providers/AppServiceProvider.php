<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->isLocal()) {
            Model::preventLazyLoading();
        }

        Gate::define('hrd', function ($user) {
            return $user->role?->name === 'hrd';
        });

        Gate::define('pimpinan', function ($user) {
            return $user->role?->name === 'pimpinan';
        });

        Gate::define('pengelola', function ($user) {
            return in_array($user->role?->name, ['hrd', 'pimpinan']);
        });

        Gate::define('karyawan', function ($user) {
            return $user->role?->name === 'karyawan';
        });
    }
}
