<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Agricultural\Model\Page;
use App\Src\UseCases\Domain\Ports\PageRepository;
use App\Src\UseCases\Infra\Sql\Model\PageModel;

class PageRepositorySql implements PageRepository
{
    public function get(string $pageId): ?Page
    {
        // TODO: Implement get() method.
    }

    public function save(Page $page)
    {
        $pageModel = new PageModel();
        $pageModel->page_id = $page->pageId();
        $pageModel->fill($page->toArray());
        $pageModel->save();
    }

}
