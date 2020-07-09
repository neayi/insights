<?php


namespace App\Src\UseCases\Domain\Auth;


use App\Exceptions\Domain\ProviderMissing;
use App\Exceptions\Domain\ProviderNotSupported;
use App\Src\UseCases\Domain\Auth\Services\CheckEmailUniqueness;
use App\Src\UseCases\Domain\Picture;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Infra\Gateway\Auth\AuthGateway;
use App\Src\UseCases\Infra\Gateway\FileStorage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

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
    public function register(string $firstname, string $lastname, string $email, ?string $provider, ?string $providerId, string $pictureUrl)
    {
        $this->checkProviderAllowed($provider, $providerId);

        $this->validateData($firstname, $lastname, $email, $pictureUrl, $providerId, $provider);
        $user = new User($id = Uuid::uuid4(), $email, $firstname, $lastname, null, '', [], [$provider => $providerId]);
        $picture = $this->handlePicture($pictureUrl);
        $user->create(null, $picture);
        $this->authGateway->log($user);
        return [
            "user_id" => $id
        ];
    }


    private function validateData(string $firstname, string $lastname, string $email, string $pictureUrl, ?string $providerId, ?string $provider): void
    {
        $rules = [
            'email' => 'string|required|email|min:2|max:255',
            'firstname' => 'string|required|min:2|max:255',
            'lastname' => 'string|required|min:2|max:255'
        ];

        $data = [
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'provider_id' => $providerId,
            'provider' => $provider,
            'picture_url' => $pictureUrl,
        ];
        $validator = Validator::make($data, $rules);
        app(CheckEmailUniqueness::class)->validateEmailUniqueness($email, $validator);
        $validator->validate();
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

    private function handlePicture(string $pictureUrl = null): ?Picture
    {
        return $pictureUrl !== null && $pictureUrl !== "" ? $this->fileStorage->uriToTmpPicture($pictureUrl) : null;
    }
}
