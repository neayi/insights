<?php


namespace App\Src\UseCases\Domain\Context\Dto;


use App\Src\UseCases\Domain\Shared\Model\Dto;

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
