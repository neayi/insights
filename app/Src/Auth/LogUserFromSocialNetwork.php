<?php


namespace App\Src\Auth;


use App\Exceptions\Domain\ProviderNotSupported;
use App\Src\Auth\Services\RegisterUserFromSocialNetworkService;
use App\Src\Shared\Gateway\AuthGateway;
use App\Src\Shared\Gateway\SocialiteGateway;
use App\Src\Users\UserRepository;

class LogUserFromSocialNetwork
{
    private $allowedProviders = ['facebook', 'twitter', 'google'];

    private $userRepository;
    private $socialiteGateway;
    private $authGateway;

    public function __construct(
        UserRepository $userRepository,
        SocialiteGateway $socialiteGateway,
        AuthGateway $authGateway
    )
    {
        $this->userRepository = $userRepository;
        $this->socialiteGateway = $socialiteGateway;
        $this->authGateway = $authGateway;
    }

    /**
     * @param string $provider
     * @throws ProviderNotSupported
     */
    public function log(string $provider)
    {
        $this->checkProviderAllowed($provider);

        $socialiteUser = $this->socialiteGateway->user($provider);

        $user = $this->userRepository->getByProvider($provider, $socialiteUser->providerId());
        if($user === null){
            app(RegisterUserFromSocialNetworkService::class)->register($provider, $socialiteUser);
            $user = $this->userRepository->getByProvider($provider, $socialiteUser->providerId());
        }
        $this->authGateway->log($user);
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