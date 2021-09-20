<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\Contracts\LedgerEntryRepositoryInterface',
            'App\Repositories\LedgerEntryRepositoryEloquent'
        );

        // Para chamar ClientRepositoryOutroORM
        // $this->app->bind(
        //     'App\Repositories\Contracts\ClientRepositoryInterface',
        //     'App\Repositories\ClientRepositoryOutroORM'
        // );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
