<?php


namespace App\Src\Insights\Insights\Domain\Ports;



use App\Src\Insights\Insights\Domain\Interactions\Page;

interface PageRepository
{
    public function get(string $pageId):?Page;
    public function getByIds(array $pagesId):array;
    public function save(Page $page);
}
