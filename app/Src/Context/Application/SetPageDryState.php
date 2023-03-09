<?php


namespace App\Src\Context\Application;


use App\Src\Context\Domain\Page;
use App\Src\Context\Domain\PageRepository;

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
        $this->pageRepository->save($page);
    }
}
