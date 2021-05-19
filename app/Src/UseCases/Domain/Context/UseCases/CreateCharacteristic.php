<?php


namespace App\Src\UseCases\Domain\Context\UseCases;


use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Exceptions\CharacteristicAlreadyExists;
use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;
use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;

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
        if(isset($characteristic)){
            throw new CharacteristicAlreadyExists();
        }
        $characteristic = new Characteristic($id, $type, $title, false);
        $characteristic->create();

        $user = $this->authGateway->current();
        $context = $this->contextRepository->getByUser($user->id());
        $context->addCharacteristics([$id]);
    }
}
