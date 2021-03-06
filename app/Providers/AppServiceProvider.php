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

        $this->app->bind(
            'App\Repositories\Contracts\TransitionTypeRepositoryInterface',
            'App\Repositories\TransitionTypeRepositoryEloquent'
        );

        $this->app->bind(
            'App\Repositories\Contracts\LedgerGroupRepositoryInterface',
            'App\Repositories\LedgerGroupRepositoryEloquent'
        );

        $this->app->bind(
            'App\Repositories\Contracts\FixedCostRepositoryInterface',
            'App\Repositories\FixedCostRepositoryEloquent'
        );

        $this->app->bind(
            'App\Repositories\Contracts\TaskGroupRepositoryInterface',
            'App\Repositories\TaskGroupRepositoryEloquent'
        );

        $this->app->bind(
            'App\Repositories\Contracts\TaskRepositoryInterface',
            'App\Repositories\TaskRepositoryEloquent'
        );

        $this->app->bind(
            'App\Repositories\Contracts\EventRepositoryInterface',
            'App\Repositories\EventRepositoryEloquent'
        );

        $this->app->bind(
            'App\Repositories\Contracts\DiagramRepositoryInterface',
            'App\Repositories\DiagramRepositoryEloquent'
        );

        $this->app->bind(
            'App\Repositories\Contracts\CollectionItemImageRepositoryInterface',
            'App\Repositories\CollectionItemImageRepositoryEloquent'
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
