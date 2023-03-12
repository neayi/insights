<?php


namespace App\Src\Auth;


use App\Exceptions\Domain\ProviderMissing;
use App\Exceptions\Domain\ProviderNotSupported;
use App\Src\Auth\Services\RegisterUserFromSocialNetworkService;
use App\Src\Shared\Gateway\AuthGateway;
use App\Src\Shared\Gateway\FileStorage;
use App\Src\Users\Domain\UserRepository;

class RegisterUserAfterErrorWithSocialNetwork
{
    private $allowedProviders = ['facebook', 'twitter', 'google'];

    private $userRepository;
    private $fileStorage;
    private $authGateway;

    public function __construct(
        UserRepository $userRepository,
        FileStorage $fileStorage,
        AuthGateway $authGateway
    )
    {
        $this->userRepository = $userRepository;
        $this->fileStorage = $fileStorage;
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
