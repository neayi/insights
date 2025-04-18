<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Model;

use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Domain\Ports\GeoLocationByPostalCode;

class Context
{
    private ContextRepository $contextRepository;

    private GeoLocationByPostalCode $geoLocationByPostalCode;

    public function __construct(
        private string $uid,
        private array $characteristics = [],
        private ?string $description = null,
        private ?string $sector = null,
        private ?string $structure = null,
        private ?string $country = null,
        private ?string $postalCode = null,
        private ?float $latitude = null,
        private ?float $longitude = null,
        private ?string $departmentNumber = null,
    )
    {
        if ('' === $this->country) {
            $this->country = null;
        }
        if ('' === $this->postalCode) {
            $this->postalCode = null;
        }

        $this->contextRepository = app(ContextRepository::class);
        $this->geoLocationByPostalCode = app(geoLocationByPostalCode::class);
    }

    public function id():string
    {
        return $this->uid;
    }

    public function update(array $params, string $userId)
    {
        if (array_key_exists('country', $params) && array_key_exists('postal_code', $params)) {
            $mustResolveGeolocation = $params['country'] !== $this->country || $params['postal_code'] !== $this->postalCode;

            $this->country = $params['country'];
            $this->postalCode = $params['postal_code'];

            if ($mustResolveGeolocation) {
                $this->resolveGeolocation();
            }
        }

        $this->description = $params['description'] ?? $this->description;
        $this->characteristics = $params['characteristics'] ?? $this->characteristics;
        $this->sector = $params['sector'] ?? $this->sector;
        $this->structure = $params['structure'] ?? $this->structure;

        $this->contextRepository->update($this, $userId);
    }

    public function resolveGeolocation(): void
    {
        if (empty($this->country) || empty($this->postalCode)) {
            $this->latitude = null;
            $this->longitude = null;
            $this->departmentNumber = null;

            return;
        }

        $geolocationInfos = $this->geoLocationByPostalCode->getGeolocationByPostalCode($this->country, $this->postalCode);

        $this->latitude = $geolocationInfos['latitude'];
        $this->longitude = $geolocationInfos['longitude'];
        $this->departmentNumber = $geolocationInfos['department_number'];
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
            'country' => $this->country,
            'department_number' => $this->departmentNumber,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
