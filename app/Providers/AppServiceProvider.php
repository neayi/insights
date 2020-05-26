<?php

namespace App\Providers;

use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Infra\Gateway\InMemoryPictureHandler;
use App\Src\UseCases\Infra\Gateway\PictureHandler;
use App\Src\UseCases\Infra\Gateway\StoragePictureHandler;
use App\Src\UseCases\Infra\InMemory\InMemoryOrganizationRepository;
use App\Src\UseCases\Infra\Sql\SqlOrganizationRepository;
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
        if(config('app.env') === 'testing'){
            $this->app->singleton(OrganizationRepository::class, InMemoryOrganizationRepository::class);
            $this->app->singleton(PictureHandler::class, InMemoryPictureHandler::class);
        }
        if(config('app.env') === 'testing-ti' || config('app.env') === 'local'){
            $this->app->singleton(OrganizationRepository::class, SqlOrganizationRepository::class);
            $this->app->singleton(PictureHandler::class, StoragePictureHandler::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
