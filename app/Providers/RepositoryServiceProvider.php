<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository($app->make('App\Models\User'));
        });
        
        // Registra aquí otros repositorios
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 