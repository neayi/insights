<?php

namespace App\Providers;

use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Infra\Gateway\Auth\AuthGateway;
use App\Src\UseCases\Infra\Gateway\Auth\InMemoryAuthGateway;
use App\Src\UseCases\Infra\Gateway\Auth\SessionAuthGateway;
use App\Src\UseCases\Infra\Gateway\FileStorage;
use App\Src\UseCases\Infra\Gateway\InMemoryFileStorage;
use App\Src\UseCases\Infra\Gateway\InMemoryPictureHandler;
use App\Src\UseCases\Infra\Gateway\PictureHandler;
use App\Src\UseCases\Infra\Gateway\StoragePictureHandler;
use App\Src\UseCases\Infra\InMemory\InMemoryOrganizationRepository;
use App\Src\UseCases\Infra\InMemory\InMemoryUserRepository;
use App\Src\UseCases\Infra\Sql\SqlOrganizationRepository;
use App\Src\UseCases\Infra\Sql\UserRepositorySql;
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
            $this->app->singleton(UserRepository::class, InMemoryUserRepository::class);
            $this->app->singleton(FileStorage::class, InMemoryFileStorage::class);
            $this->app->singleton(AuthGateway::class, InMemoryAuthGateway::class);
        }
        if(config('app.env') === 'testing-ti'){
            $this->app->singleton(OrganizationRepository::class, SqlOrganizationRepository::class);
            $this->app->singleton(PictureHandler::class, InMemoryPictureHandler::class);
            $this->app->singleton(UserRepository::class, InMemoryUserRepository::class);
            $this->app->singleton(AuthGateway::class, InMemoryAuthGateway::class);
        }

        if(config('app.env') === 'local'){
            $this->app->singleton(OrganizationRepository::class, SqlOrganizationRepository::class);
            $this->app->singleton(PictureHandler::class, StoragePictureHandler::class);
            $this->app->singleton(UserRepository::class, UserRepositorySql::class);
            $this->app->singleton(AuthGateway::class, SessionAuthGateway::class);
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
