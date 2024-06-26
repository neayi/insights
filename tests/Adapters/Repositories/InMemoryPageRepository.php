<?php


namespace Tests\Adapters\Repositories;


use App\Src\UseCases\Domain\Context\Model\Page;
use App\Src\UseCases\Domain\Ports\PageRepository;

class InMemoryPageRepository implements PageRepository
{
    private $pages = [];

    public function get(string $pageId):?Page
    {
        return isset($this->pages[$pageId]) ? clone $this->pages[$pageId] : null;
    }

    public function save(Page $page)
    {
        $this->pages[$page->pageId()] = $page;
    }

    public function getByIds(array $pagesId): array
    {
        $pages = [];
        foreach ($this->pages as $page){
            if(in_array($page->pageId(), $pagesId)) {
                $pages[] = $page;
            }
        }
        return $pages;
    }


}
