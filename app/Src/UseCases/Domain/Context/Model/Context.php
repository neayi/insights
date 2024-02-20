<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Model;

use App\Src\UseCases\Domain\Ports\ContextRepository;

class Context
{
    private $contextRepository;

    public function __construct(
        private string $uid,
        private string $postalCode,
        private array $characteristics = [],
        private ?string $description = null,
        private ?string $sector = null,
        private ?string $structure = null,
        private array $coordinates = [],
        private ?string $countryCode = ''
    )
    {
        $this->contextRepository = app(ContextRepository::class);
    }

    public function id():string
    {
        return $this->uid;
    }

    public function update(array $params, string $userId)
    {
        if (isset($params['postal_code']) && $this->postalCode !== $params['postal_code']) {
            $this->postalCode = $params['postal_code'];
            $this->coordinates = $params['coordinates'] ?? $this->coordinates;
            $this->countryCode = $params['country_code'] ?? $this->countryCode;
        }
        $this->description = $params['description'] ?? $this->description;
        $this->characteristics = $params['characteristics'] ?? $this->characteristics;
        $this->sector = $params['sector'] ?? $this->sector;
        $this->structure = $params['structure'] ?? $this->structure;
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
            'country' => $this->countryCode,
            'department_number' => $this->countryCode === 'FR' ? (new PostalCode($this->postalCode))->department() : null
        ];
    }
}
