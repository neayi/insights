<?php


namespace App\Src\UseCases\Infra\Sql;


use App\Src\UseCases\Domain\Context\Model\Page;
use App\Src\UseCases\Domain\Ports\PageRepository;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use App\Src\UseCases\Infra\Sql\Model\PageModel;

class PageRepositorySql implements PageRepository
{
    public function get(string $pageId): ?Page
    {
        $pageModel = PageModel::where('page_id', $pageId)->first();
        if(!isset($pageModel)){
            return null;
        }
        return new Page($pageModel->page_id);
    }

    public function getByIds(array $pagesIds): array
    {
        $pageModels = PageModel::query()
            ->whereIn('page_id', $pagesIds)
            ->get();
        foreach ($pageModels as $pageModel) {
            $pages[] = new Page(
                $pageModel->page_id,
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
