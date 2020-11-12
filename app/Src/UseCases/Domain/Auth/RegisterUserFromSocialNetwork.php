<?php


namespace App\Src\UseCases\Domain\Auth;


use App\Exceptions\Domain\ProviderNotSupported;
use App\Src\UseCases\Domain\Auth\Services\CheckEmailUniqueness;
use App\Src\UseCases\Domain\Picture;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Infra\Gateway\Auth\SocialiteGateway;
use App\Src\UseCases\Infra\Gateway\FileStorage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

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
    public function register(string $provider)
    {
        $this->checkProviderAllowed($provider);

        $socialiteUser = $this->socialiteGateway->user($provider);

        if($socialiteUser->email() !== null) {
            $user = $this->userRepository->getByEmail($socialiteUser->email());
        }
        if(isset($user)){
            $user->addProvider($provider, $socialiteUser->providerId());
            return [
                'user_id' => $user->id(),
                'provider_id' => $socialiteUser->providerId(),
            ];
        }

        $this->validateData($socialiteUser);
        return $this->createUser($socialiteUser, $provider);
    }

    private function validateData(SocialiteUser $socialiteUser): void
    {
        $rules = [
            'email' => 'string|required|email|min:2|max:255',
            'firstname' => 'string|required|min:2|max:255',
            'lastname' => 'string|required|min:2|max:255'
        ];

        $data = [
            'email' => $socialiteUser->email(),
            'firstname' => $socialiteUser->firstname(),
            'lastname' => $socialiteUser->lastname(),
            'provider_id' => $socialiteUser->providerId(),
            'picture_url' => $socialiteUser->pictureUrl()
        ];
        $validator = Validator::make($data, $rules);
        $validator->validate();
    }

    private function handlePicture(SocialiteUser $socialiteUser): ?Picture
    {
        return $socialiteUser->pictureUrl() !== null ? $this->fileStorage->uriToTmpPicture($socialiteUser->pictureUrl()) : null;
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

    /**
     * @param SocialiteUser $socialiteUser
     * @param string $provider
     * @return array
     */
    private function createUser(SocialiteUser $socialiteUser, string $provider): array
    {
        $user = new User($id = Uuid::uuid4(), $socialiteUser->email(), $socialiteUser->firstname(), $socialiteUser->lastname(), null, '', [], [$provider => $socialiteUser->providerId()]);
        $picture = $this->handlePicture($socialiteUser);
        $user->create(null, $picture);
        return [
            'user_id' => $id,
            'provider_id' => $socialiteUser->providerId(),
        ];
    }
}
