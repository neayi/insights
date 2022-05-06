<?php


namespace App\Src\UseCases\Domain\Context\Dto;


use App\Src\UseCases\Domain\Shared\Model\Dto;

class UserDto extends Dto
{
    public $userUid;
    public $firstname;
    public $lastname;
    public $discourseUsername;

    public function __construct(string $userId, string $firstname, string $lastname, string $discourseUsername)
    {
        $this->userUid = $userId;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->discourseUsername = $discourseUsername;
    }
}
