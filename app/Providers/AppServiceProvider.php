<?php

namespace App\Providers;

use App\Src\UseCases\Domain\IdentityProviderImpl;
use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Domain\Ports\IdentityProvider;
use App\Src\UseCases\Domain\Ports\InvitationRepository;
use App\Src\UseCases\Domain\Ports\ListRepository;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\Ports\UserRoleRepository;
use App\Src\UseCases\Infra\Gateway\Auth\AuthGateway;
use App\Src\UseCases\Infra\Gateway\Auth\InMemoryAuthGateway;
use App\Src\UseCases\Infra\Gateway\Auth\SessionAuthGateway;
use App\Src\UseCases\Infra\Gateway\Auth\SocialiteGateway;
use App\Src\UseCases\Infra\Gateway\FileStorage;
use App\Src\UseCases\Infra\Gateway\InMemory\InMemorySocialiteGateway;
use App\Src\UseCases\Infra\Gateway\InMemoryFileStorage;
use App\Src\UseCases\Infra\Gateway\InMemoryPictureHandler;
use App\Src\UseCases\Infra\Gateway\PictureHandler;
use App\Src\UseCases\Infra\Gateway\Real\FsFileStorage;
use App\Src\UseCases\Infra\Gateway\Real\RealSocialiteGateway;
use App\Src\UseCases\Infra\Gateway\StoragePictureHandler;
use App\Src\UseCases\Infra\InMemory\InMemoryContextRepository;
use App\Src\UseCases\Infra\InMemory\InMemoryInvitationRepository;
use App\Src\UseCases\Infra\InMemory\InMemoryOrganizationRepository;
use App\Src\UseCases\Infra\InMemory\InMemoryUserRepository;
use App\Src\UseCases\Infra\InMemory\InMemoryUserRoleRepository;
use App\Src\UseCases\Infra\Sql\ContextRepositorySql;
use App\Src\UseCases\Infra\Sql\InvitationRepositorySql;
use App\Src\UseCases\Infra\Sql\ListRepositorySql;
use App\Src\UseCases\Infra\Sql\SqlOrganizationRepository;
use App\Src\UseCases\Infra\Sql\UserRepositorySql;
use App\Src\UseCases\Infra\Sql\UserRoleRepositorySql;
use App\Src\Utils\Hash\HashGen;
use App\Src\Utils\Hash\HashGenReal;
use App\Src\Utils\Hash\InMemoryHashGen;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        $this->app->singleton(OrganizationRepository::class, SqlOrganizationRepository::class);
        $this->app->singleton(InvitationRepository::class, InvitationRepositorySql::class);
        $this->app->singleton(PictureHandler::class, StoragePictureHandler::class);
        $this->app->singleton(UserRepository::class, UserRepositorySql::class);
        $this->app->singleton(AuthGateway::class, SessionAuthGateway::class);
        $this->app->singleton(FileStorage::class, FsFileStorage::class);
        $this->app->singleton(SocialiteGateway::class, RealSocialiteGateway::class);
        $this->app->singleton(HashGen::class, HashGenReal::class);
        $this->app->singleton(UserRoleRepository::class, UserRoleRepositorySql::class);
        $this->app->singleton(ContextRepository::class, ContextRepositorySql::class);
        $this->app->singleton(ListRepository::class, ListRepositorySql::class);
    }

    private function tuBinding(): void
    {
        $this->app->singleton(IdentityProvider::class, IdentityProviderImpl::class);
        $this->app->singleton(OrganizationRepository::class, InMemoryOrganizationRepository::class);
        $this->app->singleton(PictureHandler::class, InMemoryPictureHandler::class);
        $this->app->singleton(UserRepository::class, InMemoryUserRepository::class);
        $this->app->singleton(FileStorage::class, InMemoryFileStorage::class);
        $this->app->singleton(AuthGateway::class, InMemoryAuthGateway::class);
        $this->app->singleton(SocialiteGateway::class, InMemorySocialiteGateway::class);
        $this->app->singleton(HashGen::class, InMemoryHashGen::class);
        $this->app->singleton(InvitationRepository::class, InMemoryInvitationRepository::class);
        $this->app->singleton(UserRoleRepository::class, InMemoryUserRoleRepository::class);
        $this->app->singleton(ContextRepository::class, InMemoryContextRepository::class);
    }

    private function tiBinding(): void
    {
        $this->app->singleton(IdentityProvider::class, IdentityProviderImpl::class);
        $this->app->singleton(OrganizationRepository::class, SqlOrganizationRepository::class);
        $this->app->singleton(InvitationRepository::class, InvitationRepositorySql::class);
        $this->app->singleton(PictureHandler::class, InMemoryPictureHandler::class);
        $this->app->singleton(UserRepository::class, UserRepositorySql::class);
        $this->app->singleton(AuthGateway::class, InMemoryAuthGateway::class);
        $this->app->singleton(FileStorage::class, InMemoryFileStorage::class);
        $this->app->singleton(HashGen::class, InMemoryHashGen::class);
        $this->app->singleton(SocialiteGateway::class, InMemorySocialiteGateway::class);
        $this->app->singleton(ContextRepository::class, ContextRepositorySql::class);
    }
}
