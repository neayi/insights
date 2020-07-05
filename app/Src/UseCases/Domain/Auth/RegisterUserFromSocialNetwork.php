<?php


namespace App\Src\UseCases\Domain\Auth;


use App\Exceptions\Domain\ProviderNotSupported;
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

        $this->validateData($socialiteUser);
        $user = new User($id = Uuid::uuid4(), $socialiteUser->email(), $socialiteUser->firstname(), $socialiteUser->lastname(), null, '', [], [$provider => $socialiteUser->providerId()]);
        $picture = $this->handlePicture($socialiteUser);
        $user->create(null, $picture);
        return [
            'user_id' => $id,
            'provider_id' => $socialiteUser->providerId(),
        ];
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
            'lastname' => $socialiteUser->lastname()
        ];
        $validator = Validator::make($data, $rules);
        $this->validateEmailUniqueness($socialiteUser->email(), $validator);
        $validator->validate();
    }

    private function validateEmailUniqueness(string $email, \Illuminate\Contracts\Validation\Validator $validator): void
    {
        $errors = [];
        $user = $this->userRepository->getByEmail($email);
        if (isset($user)) {
            $errors['email'] = __('validation.unique', ['attribute' => 'email']);
            $validator->after(function () use ($validator, $errors) {
                foreach ($errors as $field => $error) {
                    $validator->errors()->add($field, $error);
                }
            });
        }
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
}
