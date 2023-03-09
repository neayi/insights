<?php


namespace App\Src\Context\Infrastructure\Repository;


use App\Src\Context\Domain\Page;
use App\Src\Context\Domain\PageRepository;
use App\Src\Context\Infrastructure\Model\PageModel;

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

    public function getByIds(array $pagesIds): array
    {
        $pageModels = PageModel::query()
            ->whereIn('page_id', $pagesIds)
            ->get();
        foreach ($pageModels as $pageModel) {
            $pages[] = new Page(
                $pageModel->page_id,
                $pageModel->dry,
                $pageModel->title,
                $pageModel->type,
                $pageModel->icon
            );
        }
        return $pages ?? [];
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

    public function search(string $type, string $search):array
    {
        $pageModel = PageModel::query()
            ->where('title','LIKE', '%'.$search.'%')
            ->where('type', $type)
            ->get();
        if(!isset($pageModel)){
            return [];
        }
        return $pageModel->toArray();
    }

}
