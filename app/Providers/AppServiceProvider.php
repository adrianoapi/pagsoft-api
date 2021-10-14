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

        $this->app->bind(
            'App\Repositories\Contracts\LedgerItemRepositoryInterface',
            'App\Repositories\LedgerItemRepositoryEloquent'
        );

        $this->app->bind(
            'App\Repositories\Contracts\CollectionRepositoryInterface',
            'App\Repositories\CollectionRepositoryEloquent'
        );

        $this->app->bind(
            'App\Repositories\Contracts\CollectionItemRepositoryInterface',
            'App\Repositories\CollectionItemRepositoryEloquent'
        );

        $this->app->bind(
            'App\Repositories\Contracts\PasswordRepositoryInterface',
            'App\Repositories\PasswordRepositoryEloquent'
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
