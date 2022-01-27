<?php


namespace App\Src\Insights\Users\Application\UseCase;


use App\Exceptions\Domain\UserNotFound;
use App\Src\UseCases\Domain\Ports\UserRepository;
use Illuminate\Support\Facades\Validator;

class EditUser
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function edit(string $userId, string $email, string $firstname, string $lastname, array $picture)
    {
        $user = $this->userRepository->getById($userId);
        if(!isset($user)){
            throw new UserNotFound();
        }
        $this->validateData($userId, $firstname, $lastname, $email, $picture);

        list($pathPicture, $ext) = $this->handlePicture($picture);
        $user->update($email, $firstname, $lastname, $pathPicture, $ext);
    }

    private function validateData(string $userId, string $firstname, string $lastname, string $email, array $picture): array
    {
        $rules = [
            'firstname' => 'string|required|min:2|max:255',
            'lastname' => 'string|required|min:2|max:255',
            'email' => 'string|required|min:2|max:255|email',
            'mine_type' => 'nullable|in:image/jpeg,image/png,image/jpg'
        ];
        $data = array_merge(['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email], $picture);
        $validator = Validator::make($data, $rules);

        $this->addErrorsUniquenessEmail($userId, $email, $validator);

        $validator->validate();
        return $data;
    }

    private function addErrorsUniquenessEmail(string $userId, string $email, \Illuminate\Contracts\Validation\Validator $validator): void
    {
        $errors = [];
        $user = $this->userRepository->getByEmail($email);
        if (isset($user) && $user->id() !== $userId) {
            $errors['email'] = __('validation.unique', ['attribute' => 'email']);
        }
        $validator->after(function () use ($validator, $errors) {
            foreach ($errors as $field => $error) {
                $validator->errors()->add($field, $error);
            }
        });
    }

    /**
     * @param array $picture
     * @return array
     */
    private function handlePicture(array $picture): array
    {
        $pathPicture = isset($picture['path_picture']) ? $picture['path_picture'] : '';
        $ext = isset($picture['original_name']) && strpos($picture['original_name'], '.') ? explode('.', $picture['original_name'])[1] : 'jpg';
        return [$pathPicture, $ext];
    }
}
