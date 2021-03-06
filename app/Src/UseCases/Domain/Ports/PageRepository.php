<?php


namespace App\Src\UseCases\Domain\Ports;


use App\Src\UseCases\Domain\Agricultural\Model\Page;

interface PageRepository
{
    public function get(string $pageId):?Page;
    public function save(Page $page);
}
