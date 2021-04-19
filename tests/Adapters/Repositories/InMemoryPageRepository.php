<?php


namespace Tests\Adapters\Repositories;


use App\Src\UseCases\Domain\Agricultural\Model\Page;
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
}
