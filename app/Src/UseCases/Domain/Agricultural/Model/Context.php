<?php


namespace App\Src\UseCases\Domain\Agricultural\Model;


use App\Src\UseCases\Domain\Ports\ContextRepository;

class Context
{
    private $uid;
    private $postalCode;
    private $characteristics;
    private $description;
    private $sector;
    private $structure;
    private $contextRepository;

    public function __construct(
        string $id,
        string $postalCode,
        array $characteristics = [],
        string $description = null,
        string $sector = null,
        string $structure = null
    )
    {
        $this->uid = $id;
        $this->postalCode = $postalCode;
        $this->characteristics = $characteristics;
        $this->description = $description;
        $this->sector = $sector;
        $this->structure = $structure;
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
        $this->characteristics = $params['characteristics'] ?? $this->characteristics;
        $this->sector = $params['sector'] ?? $this->sector;
        $this->structure = $params['structure'] ?? $this->structure;
        $this->contextRepository->update($this, $userId);
    }

    public function addCharacteristics(array $characteristics)
    {
        $this->characteristics = array_merge($this->characteristics, $characteristics);
    }

    public function toArray()
    {
        return [
            'uuid' => $this->uid,
            'postal_code' => $this->postalCode,
            'farmings' => $this->characteristics,
            'description' => $this->description,
            'sector' => $this->sector,
            'structure' => $this->structure,
        ];
    }
}
