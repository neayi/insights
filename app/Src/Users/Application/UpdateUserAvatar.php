<?php


namespace App\Src\Users\Application;


use App\Src\Users\Domain\UserRepository;
use Illuminate\Support\Facades\Validator;

class UpdateUserAvatar
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $userId, array $picture)
    {
        $user = $this->userRepository->getById($userId);
        $this->validateData($picture);
        list($pathPicture, $ext) = $this->handlePicture($picture);
        return $user->updateAvatar($pathPicture, $ext);
    }

    private function validateData(array $picture): array
    {
        $rules = [
            'mine_type' => 'nullable|in:image/jpeg,image/png,image/jpg'
        ];
        $validator = Validator::make($picture, $rules);
        $validator->validate();
        return $picture;
    }

    private function handlePicture(array $picture): array
    {
        $pathPicture = isset($picture['path_picture']) ? $picture['path_picture'] : '';
        $ext = isset($picture['original_name']) && strpos($picture['original_name'], '.') ? explode('.', $picture['original_name'])[1] : 'jpg';
        return [$pathPicture, $ext];
    }
}
