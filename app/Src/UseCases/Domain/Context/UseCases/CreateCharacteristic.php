<?php


declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\UseCases;


use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;
use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;

class CreateCharacteristic
{
    public function __construct(
        private CharacteristicsRepository $characteristicRepository,
        private AuthGateway $authGateway,
        private ContextRepository $contextRepository
    ){}

    public function execute(string $id, string $type, string $title)
    {
        $user = $this->authGateway->current();
        $characteristic = $this->characteristicRepository->getBy(['title' => $title, 'type' => $type]);
        if(!isset($characteristic)){
            $characteristic = new Characteristic($id, $type, $title, false, null, $user->defaultLocale());
            $characteristic->create();
        }

        $context = $this->contextRepository->getByUser($user->id());
        $context->addCharacteristics([$characteristic->id()], $user->id());
    }
}
