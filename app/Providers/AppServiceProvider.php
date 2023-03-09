<?php

namespace App\Providers;

use App\Src\Context\Domain\CharacteristicsRepository;
use App\Src\Context\Domain\ContextRepository;
use App\Src\Context\Domain\InteractionRepository;
use App\Src\Context\Domain\PageRepository;
use App\Src\Context\Infrastructure\Repository\CharacteristicsRepositorySql;
use App\Src\Context\Infrastructure\Repository\ContextRepositorySql;
use App\Src\Context\Infrastructure\Repository\InteractionPageRepositorySql;
use App\Src\Context\Infrastructure\Repository\PageRepositorySql;
use App\Src\Organizations\Infrastructure\InvitationRepositorySql;
use App\Src\Organizations\Infrastructure\SqlOrganizationRepository;
use App\Src\Organizations\InvitationRepository;
use App\Src\Organizations\OrganizationRepository;
use App\Src\Shared\Gateway\AuthGateway;
use App\Src\Shared\Gateway\FileStorage;
use App\Src\Shared\Gateway\FsFileStorage;
use App\Src\Shared\Gateway\GetDepartmentFromPostalCode;
use App\Src\Shared\Gateway\GetDepartmentFromPostalCodeImpl;
use App\Src\Shared\Gateway\PictureHandler;
use App\Src\Shared\Gateway\SessionAuthGateway;
use App\Src\Shared\Gateway\SocialiteGateway;
use App\Src\Shared\Gateway\SocialiteGatewayImpl;
use App\Src\Shared\Gateway\StoragePictureHandler;
use App\Src\Shared\IdentityProvider;
use App\Src\Shared\Provider\IdentityProviderImpl;
use App\Src\Users\Infrastructure\UserRepositorySql;
use App\Src\Users\Infrastructure\UserRoleRepositorySql;
use App\Src\Users\UserRepository;
use App\Src\Users\UserRoleRepository;
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
use Tests\Adapters\Repositories\InMemoryInvitationRepository;
use Tests\Adapters\Repositories\InMemoryOrganizationRepository;
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
        $this->app->singleton(OrganizationRepository::class, SqlOrganizationRepository::class);
        $this->app->singleton(InvitationRepository::class, InvitationRepositorySql::class);
        $this->app->singleton(PictureHandler::class, StoragePictureHandler::class);
        $this->app->singleton(UserRepository::class, UserRepositorySql::class);
        $this->app->singleton(AuthGateway::class, SessionAuthGateway::class);
        $this->app->singleton(FileStorage::class, FsFileStorage::class);
        $this->app->singleton(SocialiteGateway::class, SocialiteGatewayImpl::class);
        $this->app->singleton(HashGen::class, HashGenReal::class);
        $this->app->singleton(ContextRepository::class, ContextRepositorySql::class);
        $this->app->singleton(CharacteristicsRepository::class, CharacteristicsRepositorySql::class);
        $this->app->singleton(PageRepository::class, PageRepositorySql::class);
        $this->app->singleton(InteractionRepository::class, InteractionPageRepositorySql::class);
        $this->app->singleton(GetDepartmentFromPostalCode::class, GetDepartmentFromPostalCodeImpl::class);
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
        $this->app->singleton(ContextRepository::class, InMemoryContextRepository::class);
        $this->app->singleton(CharacteristicsRepository::class, InMemoryCharacteristicRepository::class);
        $this->app->singleton(PageRepository::class, InMemoryPageRepository::class);
        $this->app->singleton(InteractionRepository::class, InMemoryInteractionRepository::class);
        $this->app->singleton(GetDepartmentFromPostalCode::class, InMemoryGetDepartmentsFromPostalCode::class);
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
        $this->app->singleton(CharacteristicsRepository::class, CharacteristicsRepositorySql::class);
        $this->app->singleton(PageRepository::class, PageRepositorySql::class);
        $this->app->singleton(InteractionRepository::class, InteractionPageRepositorySql::class);
        $this->app->singleton(GetDepartmentFromPostalCode::class, InMemoryGetDepartmentsFromPostalCode::class);
    }
}
