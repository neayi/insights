<?php


namespace App\Src\UseCases\Domain\Context\Dto;


use App\Src\UseCases\Domain\Shared\Model\Dto;

class UserDto extends Dto
{
    public $userUid;
    public $firstname;
    public $lastname;
    public $pictureUrl;
    public $email;
    public $roles;
    public $discourse;

    public function __construct(string $userId, string $firstname, string $lastname, string $email, array $roles, ?string $pictureUrl, array $discourse)
    {
        $this->userUid = $userId;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->pictureUrl = $pictureUrl;
        $this->email = $email;
        $this->roles = $roles;
        $this->discourse = $discourse;
    }
}
