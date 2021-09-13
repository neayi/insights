<?php


namespace App\Src\UseCases\Domain\Auth;


use App\Exceptions\Domain\ProviderNotSupported;
use App\Src\UseCases\Domain\Auth\Services\RegisterUserFromSocialNetworkService;
use App\Src\UseCases\Domain\Shared\Gateway\SocialiteGateway;

class RegisterUserFromSocialNetwork
{
    private $allowedProviders = ['facebook', 'twitter', 'google'];

    private $socialiteGateway;

    public function __construct(
        SocialiteGateway $socialiteGateway
    )
    {
        $this->socialiteGateway = $socialiteGateway;
    }

    /**
     * @param string $provider
     * @return array
     * @throws ProviderNotSupported
     */
    public function register(string $provider):array
    {
        $this->checkProviderAllowed($provider);

        $socialiteUser = $this->socialiteGateway->user($provider);

        return app(RegisterUserFromSocialNetworkService::class)->register($provider, $socialiteUser);
    }

    /**
     * @param string $provider
     * @throws ProviderNotSupported
     */
    private function checkProviderAllowed(string $provider): void
    {
        if (!in_array($provider, $this->allowedProviders)) {
            throw new ProviderNotSupported();
        }
    }

}
