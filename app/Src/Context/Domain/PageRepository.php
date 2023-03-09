<?php


namespace App\Src\Context\Domain;


interface PageRepository
{
    public function get(string $pageId):?Page;
    public function getByIds(array $pagesId):array;
    public function save(Page $page);
}
