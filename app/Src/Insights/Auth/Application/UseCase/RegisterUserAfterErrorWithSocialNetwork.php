<?php


namespace App\Src\Insights\Auth\Application\UseCase;


use App\Exceptions\Domain\ProviderMissing;
use App\Exceptions\Domain\ProviderNotSupported;
use App\Src\Insights\Auth\Domain\Services\RegisterUserFromSocialNetworkService;
use App\Src\Insights\Auth\Domain\SocialiteUser;
use App\Src\Insights\Insights\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;

class RegisterUserAfterErrorWithSocialNetwork
{
    private $allowedProviders = ['facebook', 'twitter', 'google'];

    private $userRepository;
    private $authGateway;

    public function __construct(
        UserRepository $userRepository,
        AuthGateway $authGateway
    )
    {
        $this->userRepository = $userRepository;
        $this->authGateway = $authGateway;
    }

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string|null $provider
     * @param string|null $providerId
     * @param string $pictureUrl
     * @return array
     * @throws ProviderMissing
     * @throws ProviderNotSupported
     */
    public function register(string $firstname, string $lastname, string $email, ?string $provider, ?string $providerId, string $pictureUrl):array
    {
        $this->checkProviderAllowed($provider, $providerId);

        $socialiteUser = new SocialiteUser($providerId, $email, $firstname, $lastname, $pictureUrl);
        $result = app(RegisterUserFromSocialNetworkService::class)->register($provider, $socialiteUser);
        $user = $this->userRepository->getById($result['user_id']);
        $this->authGateway->log($user);
        return $result;
    }

    /**
     * @param string|null $provider
     * @param string|null $providerId
     * @throws ProviderMissing
     * @throws ProviderNotSupported
     */
    private function checkProviderAllowed(?string $provider, ?string $providerId): void
    {
        if($provider === null || $providerId === null){
            throw new ProviderMissing();
        }
        if (!in_array($provider, $this->allowedProviders)) {
            throw new ProviderNotSupported();
        }
    }
}
