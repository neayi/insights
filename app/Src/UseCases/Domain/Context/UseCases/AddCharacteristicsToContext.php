<?php


namespace App\Src\UseCases\Domain\Context\UseCases;


use App\Src\UseCases\Domain\Context\Model\Characteristic;
use App\Src\UseCases\Domain\Ports\CharacteristicsRepository;
use App\Src\UseCases\Domain\Ports\ContextRepository;
use App\Src\UseCases\Domain\Ports\PageRepository;
use App\Src\UseCases\Domain\Shared\Gateway\AuthGateway;
use Ramsey\Uuid\Uuid;

class AddCharacteristicsToContext
{
    private $authGateway;
    private $contextRepository;
    private $pageRepository;
    private $characteristicsRepository;

    public function __construct(
        AuthGateway $authGateway,
        ContextRepository $contextRepository,
        PageRepository $pageRepository,
        CharacteristicsRepository $characteristicsRepository
    )
    {
        $this->authGateway = $authGateway;
        $this->contextRepository = $contextRepository;
        $this->pageRepository = $pageRepository;
        $this->characteristicsRepository = $characteristicsRepository;
    }

    public function execute(array $pagesIds)
    {
        $user = $this->authGateway->current();
        $context = $this->contextRepository->getByUser($user->id());
        $pages = $this->pageRepository->getByIds($pagesIds);

        $characteristics = [];
        foreach ($pages as $page){
            $characteristic = $this->characteristicsRepository->getByPageId($page->pageId());
            if(!isset($characteristic)){
                $type = $page->type() === 'culture' ? Characteristic::FARMING_TYPE : Characteristic::CROPPING_SYSTEM;
                $characteristic = new Characteristic(Uuid::uuid4(), $type, $page->title(), false);
                $characteristic->create();
            }
            $characteristics[] = $characteristic->id();
        }
        $context->addCharacteristics($characteristics, $user->id());
    }
}
