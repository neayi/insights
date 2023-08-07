<?php

namespace App\Providers;

use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Domain\Ports\IdentityProvider;
use App\Src\UseCases\Domain\Ports\InteractionRepository;
use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;
use App\Src\UseCases\Domain\Ports\PageRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\Ports\UserRoleRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\Shared\Gateway\FileStorage;
use App\Src\UseCases\Domain\Shared\Gateway\PictureHandler;
use App\Src\UseCases\Domain\Shared\Gateway\SocialiteGateway;
use App\Src\UseCases\Domain\Shared\Provider\IdentityProviderImpl;
use App\Src\UseCases\Domain\System\GetDepartmentFromPostalCode;
use App\Src\UseCases\Domain\System\GetDepartmentFromPostalCodeImpl;
use App\Src\UseCases\Infra\Gateway\FsFileStorage;
use App\Src\UseCases\Infra\Gateway\SessionAuthGateway;
use App\Src\UseCases\Infra\Gateway\SocialiteGatewayImpl;
use App\Src\UseCases\Infra\Gateway\StoragePictureHandler;
use App\Src\UseCases\Infra\Sql\ContextRepositorySql;
use App\Src\UseCases\Infra\Sql\InteractionPageRepositorySql;
use App\Src\UseCases\Infra\Sql\CharacteristicsRepositorySql;
use App\Src\UseCases\Infra\Sql\PageRepositorySql;
use App\Src\UseCases\Infra\Sql\UserRepositorySql;
use App\Src\UseCases\Infra\Sql\UserRoleRepositorySql;
use App\Src\Utils\Hash\HashGen;
use App\Src\Utils\Hash\HashGenReal;
use App\Src\Utils\Hash\InMemoryHashGen;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Tests\Adapters\Gateway\InMemoryAuthGateway;
use Tests\Adapters\Gateway\InMemoryFileStorage;
use Tests\Adapters\Gateway\InMemoryGetDepartmentsFromPostalCode;
use Tests\Adapters\Gateway\InMemoryPictureHandler;
use Tests\Adapters\Gateway\InMemorySocialiteGateway;
use Tests\Adapters\Repositories\InMemoryCharacteristicRepository;
use Tests\Adapters\Repositories\InMemoryContextRepository;
use Tests\Adapters\Repositories\InMemoryInteractionRepository;
use Tests\Adapters\Repositories\InMemoryPageRepository;
use Tests\Adapters\Repositories\InMemoryUserRepository;
use Tests\Adapters\Repositories\InMemoryUserRoleRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerHelpers();

        if(config('app.env') === 'testing'){
            $this->tuBinding();
        }
        if(config('app.env') === 'testing-ti'){
            $this->tiBinding();
        }
        if(config('app.env') === 'local' || config('app.env') === 'production'){
            $this->prodBinding();
        }
    }

    public function boot()
    {
        if(config('app.env') !== 'testing' && config('app.env') !== 'testing-ti') {
            Schema::defaultStringLength(191);
        }

        if(config('app.env') === 'local' || config('app.env') === 'production'){
            URL::forceScheme('https');
        }
    }

    private function registerHelpers(): void
    {
        foreach (glob(app_path() . '/Src/Utils/Helpers/*.php') as $filename) {
            require_once($filename);
        }
    }

    private function prodBinding(): void
    {
        $this->app->singleton(IdentityProvider::class, IdentityProviderImpl::class);
        $this->app->singleton(PictureHandler::class, StoragePictureHandler::class);
        $this->app->singleton(UserRepository::class, UserRepositorySql::class);
        $this->app->singleton(AuthGateway::class, SessionAuthGateway::class);
        $this->app->singleton(FileStorage::class, FsFileStorage::class);
        $this->app->singleton(SocialiteGateway::class, SocialiteGatewayImpl::class);
        $this->app->singleton(HashGen::class, HashGenReal::class);
        $this->app->singleton(UserRoleRepository::class, UserRoleRepositorySql::class);
        $this->app->singleton(ContextRepository::class, ContextRepositorySql::class);
        $this->app->singleton(CharacteristicsRepository::class, CharacteristicsRepositorySql::class);
        $this->app->singleton(PageRepository::class, PageRepositorySql::class);
        $this->app->singleton(InteractionRepository::class, InteractionPageRepositorySql::class);
        $this->app->singleton(GetDepartmentFromPostalCode::class, GetDepartmentFromPostalCodeImpl::class);
    }

    private function tuBinding(): void
    {
        $this->app->singleton(IdentityProvider::class, IdentityProviderImpl::class);
        $this->app->singleton(PictureHandler::class, InMemoryPictureHandler::class);
        $this->app->singleton(UserRepository::class, InMemoryUserRepository::class);
        $this->app->singleton(FileStorage::class, InMemoryFileStorage::class);
        $this->app->singleton(AuthGateway::class, InMemoryAuthGateway::class);
        $this->app->singleton(SocialiteGateway::class, InMemorySocialiteGateway::class);
        $this->app->singleton(HashGen::class, InMemoryHashGen::class);
        $this->app->singleton(UserRoleRepository::class, InMemoryUserRoleRepository::class);
        $this->app->singleton(ContextRepository::class, InMemoryContextRepository::class);
        $this->app->singleton(CharacteristicsRepository::class, InMemoryCharacteristicRepository::class);
        $this->app->singleton(PageRepository::class, InMemoryPageRepository::class);
        $this->app->singleton(InteractionRepository::class, InMemoryInteractionRepository::class);
        $this->app->singleton(GetDepartmentFromPostalCode::class, InMemoryGetDepartmentsFromPostalCode::class);
    }

    private function tiBinding(): void
    {
        $this->app->singleton(IdentityProvider::class, IdentityProviderImpl::class);
        $this->app->singleton(PictureHandler::class, InMemoryPictureHandler::class);
        $this->app->singleton(UserRepository::class, UserRepositorySql::class);
        $this->app->singleton(AuthGateway::class, InMemoryAuthGateway::class);
        $this->app->singleton(FileStorage::class, InMemoryFileStorage::class);
        $this->app->singleton(HashGen::class, InMemoryHashGen::class);
        $this->app->singleton(SocialiteGateway::class, InMemorySocialiteGateway::class);
        $this->app->singleton(ContextRepository::class, ContextRepositorySql::class);
        $this->app->singleton(CharacteristicsRepository::class, CharacteristicsRepositorySql::class);
        $this->app->singleton(PageRepository::class, PageRepositorySql::class);
        $this->app->singleton(InteractionRepository::class, InteractionPageRepositorySql::class);
        $this->app->singleton(GetDepartmentFromPostalCode::class, InMemoryGetDepartmentsFromPostalCode::class);
    }
}
