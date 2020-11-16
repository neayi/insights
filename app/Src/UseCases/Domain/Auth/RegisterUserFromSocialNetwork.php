<?php


namespace App\Src\UseCases\Domain\Auth;


use App\Exceptions\Domain\ProviderNotSupported;
use App\Src\UseCases\Domain\Auth\Services\RegisterUserFromSocialNetworkService;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Infra\Gateway\Auth\SocialiteGateway;
use App\Src\UseCases\Infra\Gateway\FileStorage;

class RegisterUserFromSocialNetwork
{
    private $allowedProviders = ['facebook', 'twitter', 'google'];

    private $userRepository;
    private $socialiteGateway;
    private $fileStorage;

    public function __construct(
        UserRepository $userRepository,
        SocialiteGateway $socialiteGateway,
        FileStorage $fileStorage
    )
    {
        $this->userRepository = $userRepository;
        $this->socialiteGateway = $socialiteGateway;
        $this->fileStorage = $fileStorage;
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
