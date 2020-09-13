<?php


namespace App\Src\UseCases\Domain\Auth;


use App\Exceptions\Domain\ProviderNotSupported;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Infra\Gateway\Auth\AuthGateway;
use App\Src\UseCases\Infra\Gateway\Auth\SocialiteGateway;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

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
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
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
