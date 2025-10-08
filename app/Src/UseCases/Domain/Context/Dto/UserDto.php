<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Dto;

use App\Src\UseCases\Domain\Shared\Model\Dto;

class UserDto extends Dto
{
    public $userUid;
    public $firstname;
    public $lastname;
    public $defaultAvatar;
    // public $discourseUsername;

    public function __construct(
        string $userId,
        string $firstname,
        string $lastname,
        bool $defaultAvatar
    )
    {
        $this->userUid = $userId;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->defaultAvatar = $defaultAvatar;
    }
}
