<?php


namespace App\Src\UseCases\Domain\Auth;


use App\Exceptions\Domain\ProviderNotSupported;
use App\Src\UseCases\Domain\Auth\Services\RegisterUserFromSocialNetworkService;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use App\Src\UseCases\Domain\Shared\Gateway\SocialiteGateway;

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
        } else {
            $this->userRepository->verifyEmail($user->id());
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
