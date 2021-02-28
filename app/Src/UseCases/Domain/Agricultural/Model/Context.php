<?php


namespace App\Src\UseCases\Domain\Agricultural\Model;


use App\Src\UseCases\Domain\Ports\ContextRepository;

class Context
{
    private $uid;
    private $postalCode;
    private $farmingType;
    private $contextRepository;

    public function __construct(string $id, string $postalCode, array $farmingType = [])
    {
        $this->uid = $id;
        $this->postalCode = $postalCode;
        $this->farmingType = $farmingType;
        $this->contextRepository = app(ContextRepository::class);
    }

    public function create(string $userId)
    {
        $this->contextRepository->add($this, $userId);
    }

    public function toArray()
    {
        return [
            'uuid' => $this->uid,
            'postal_code' => $this->postalCode,
            'farmings' => $this->farmingType,
        ];
    }
}
