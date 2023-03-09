<?php


namespace App\Src\Context\Domain;


class Context
{
    private $uid;
    private $postalCode;
    private $characteristics;
    private $description;
    private $sector;
    private $structure;
    private $departmentNumber;
    private $coordinates;

    private $contextRepository;

    public function __construct(
        string $id,
        string $postalCode,
        array $characteristics = [],
        string $description = null,
        string $sector = null,
        string $structure = null,
        string $departmentNumber = null,
        array $coordinates = []
    )
    {
        $this->uid = $id;
        $this->postalCode = $postalCode;
        $this->characteristics = $characteristics;
        $this->description = $description;
        $this->sector = $sector;
        $this->structure = $structure;
        $this->departmentNumber = $departmentNumber;
        $this->coordinates = $coordinates;
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
        $this->departmentNumber = $params['department_number'] ?? $this->departmentNumber;
        $this->coordinates = $params['coordinates'] ?? $this->coordinates;
        $this->contextRepository->update($this, $userId);
    }

    public function addCharacteristics(array $characteristics, string $userId)
    {
        $this->characteristics = array_values(array_unique(array_merge($this->characteristics, $characteristics)));
        $this->contextRepository->update($this, $userId);
    }

    public function toArray()
    {
        return [
            'uuid' => $this->uid,
            'postal_code' => $this->postalCode,
            'characteristics' => $this->characteristics,
            'description' => $this->description,
            'sector' => $this->sector,
            'structure' => $this->structure,
            'coordinates' => $this->coordinates,
            'department_number' => $this->departmentNumber,
        ];
    }
}
