<?php


namespace App\Src\Context\Application;


use App\Src\Context\Domain\CharacteristicsRepository;
use App\Src\Context\Domain\ContextRepository;
use App\Src\Context\Domain\PageRepository;
use App\Src\Shared\Gateway\AuthGateway;

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
                $characteristic = $page->createCharacteristicAssociated();
            }
            $characteristics[] = $characteristic->id();
        }
        $context->addCharacteristics($characteristics, $user->id());
    }
}
