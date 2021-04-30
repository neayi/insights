<?php


namespace App\Src\UseCases\Domain\Agricultural\Model;


use App\Src\UseCases\Domain\Ports\ContextRepository;

class Context
{
    private $uid;
    private $postalCode;
    private $farmingType;
    private $description;
    private $contextRepository;

    public function __construct(string $id, string $postalCode, array $farmingType = [], string $description = null)
    {
        $this->uid = $id;
        $this->postalCode = $postalCode;
        $this->farmingType = $farmingType;
        $this->description = $description;
        $this->contextRepository = app(ContextRepository::class);
    }

    public function id():string
    {
        return $this->uid;
    }

    public function create(string $userId)
    {
        $this->contextRepository->add($this, $userId);
    }

    public function update(array $params, string $userId)
    {
        $this->description = $params['description'] ?? $this->description;
        $this->postalCode = $params['postal_code'] ?? $this->postalCode;
        $this->contextRepository->update($this, $userId);
    }

    public function toArray()
    {
        return [
            'uuid' => $this->uid,
            'postal_code' => $this->postalCode,
            'farmings' => $this->farmingType,
            'description' => $this->description,
        ];
    }
}
