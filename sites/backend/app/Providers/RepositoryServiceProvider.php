<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\Bindings\RepositoryBindings;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RepositoryBindings::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
