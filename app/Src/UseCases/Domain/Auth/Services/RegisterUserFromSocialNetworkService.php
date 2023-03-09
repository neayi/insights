<?php


namespace App\Src\UseCases\Domain\Auth\Services;


use App\Src\Shared\Gateway\FileStorage;
use App\Src\Shared\Model\Picture;
use App\Src\UseCases\Domain\Auth\SocialiteUser;
use App\Src\UseCases\Domain\Ports\UserRepository;
use App\Src\UseCases\Domain\User;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class RegisterUserFromSocialNetworkService
{
    private $userRepository;
    private $fileStorage;

    public function __construct(
        UserRepository $userRepository,
        FileStorage $fileStorage
    )
    {
        $this->userRepository = $userRepository;
        $this->fileStorage = $fileStorage;
    }

    public function register(string $provider, SocialiteUser $socialiteUser)
    {
        $user = $this->userRepository->getByProvider($provider, $socialiteUser->providerId());
        if(isset($user)){
            return [
                'user_id' => $user->id(),
                'provider_id' => $socialiteUser->providerId(),
                'state' => 'user_already_exist'
            ];
        }

        if($socialiteUser->email() !== null  && $socialiteUser->email() != "") {
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
            'email' => 'string|email|min:2|max:255|nullable',
            'firstname' => 'string|min:2|max:255',
            'lastname' => 'string|min:2|max:255'
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
     * @param SocialiteUser $socialiteUser
     * @param string $provider
     * @return array
     */
    private function createUser(SocialiteUser $socialiteUser, string $provider): array
    {
        $user = new User($id = Uuid::uuid4(), $socialiteUser->email(), $socialiteUser->firstname(), $socialiteUser->lastname(), null, '', [], [$provider => $socialiteUser->providerId()]);
        $picture = $this->handlePicture($socialiteUser);
        $user->create(null, $picture);

        // If the user has an email, we take it for granted that Google or Facebook has already verified it:
        $email = $socialiteUser->email();
        if (!empty($email))
            $this->userRepository->verifyEmail($id);

        return [
            'user_id' => $id,
            'provider_id' => $socialiteUser->providerId(),
        ];
    }
}
