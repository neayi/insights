<?php


namespace App\Src\UseCases\Domain\Ports;


use App\Src\UseCases\Domain\Context\Model\Page;

interface PageRepository
{
    public function get(string $pageId):?Page;
    public function getByIds(array $pagesId):array;
    public function save(Page $page);
}
