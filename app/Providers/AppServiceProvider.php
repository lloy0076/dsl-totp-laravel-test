<?php

namespace App\Providers;

use App\Repositories\DataStorageRepository;
use App\Repositories\SessionStorageRepository;
use App\Repositories\StorageRepositoryContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        //StorageRepositoryContract::class => SessionStorageRepository::class,
        StorageRepositoryContract::class => DataStorageRepository::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
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
