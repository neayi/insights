<?php


namespace App\Src\Context\Application;


use App\Src\Context\Domain\Characteristic;
use App\Src\Context\Domain\CharacteristicsRepository;
use App\Src\Context\Domain\ContextRepository;
use App\Src\Shared\Gateway\AuthGateway;

class CreateCharacteristic
{
    private $characteristicRepository;
    private $authGateway;
    private $contextRepository;

    public function __construct(
        CharacteristicsRepository $characteristicRepository,
        AuthGateway $authGateway,
        ContextRepository $contextRepository
    )
    {
        $this->characteristicRepository = $characteristicRepository;
        $this->authGateway = $authGateway;
        $this->contextRepository = $contextRepository;
    }

    public function execute(string $id, string $type, string $title)
    {
        $characteristic = $this->characteristicRepository->getBy(['title' => $title, 'type' => $type]);
        if(!isset($characteristic)){
            $characteristic = new Characteristic($id, $type, $title, false);
            $characteristic->create();
            $this->characteristicRepository->save($characteristic);
        }

        $user = $this->authGateway->current();
        $context = $this->contextRepository->getByUser($user->id());
        $context->addCharacteristics([$characteristic->id()], $user->id());
    }
}
