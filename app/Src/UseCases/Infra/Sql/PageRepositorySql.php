<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Agricultural\Model\Page;
use App\Src\UseCases\Domain\Ports\PageRepository;
use App\Src\UseCases\Infra\Sql\Model\PageModel;

class PageRepositorySql implements PageRepository
{
    public function get(string $pageId): ?Page
    {
        $pageModel = PageModel::where('page_id', $pageId)->first();
        if(!isset($pageModel)){
            return null;
        }
        return new Page($pageModel->page_id, $pageModel->dry);
    }

    public function save(Page $page)
    {
        $pageModel = PageModel::where('page_id', $page->pageId())->first();
        if(!isset($pageModel)){
            $pageModel = new PageModel();
        }
        $pageModel->page_id = $page->pageId();
        $pageModel->fill($page->toArray());
        $pageModel->save();
    }

}
