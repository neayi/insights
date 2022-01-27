<?php


namespace App\Src\Insights\Insights\Application\UseCase\Interactions;


use App\Src\Insights\Insights\Domain\Interactions\Page;
use App\Src\UseCases\Domain\Ports\PageRepository;

class SetPageDryState
{
    public $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function execute(int $pageId)
    {
        $page = $this->pageRepository->get($pageId);
        if($page === null){
            $page = new Page($pageId, true);
            $this->pageRepository->save($page);
            return;
        }
        $page->setOnDryState();
    }
}
