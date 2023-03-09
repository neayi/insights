<?php


namespace App\Src\Context\Application\Dto;


use App\Src\Shared\Model\Dto;

class FollowerDto extends Dto
{
    public $user;
    public $context;
    public $interaction;

    public function __construct(
        UserDto $userDto,
        ContextDto $contextDto,
        InteractionDto $interactionDto
    )
    {
        $this->user = $userDto;
        $this->context = $contextDto;
        $this->interaction = $interactionDto;
    }
}
