<?php


namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\UseCases\Domain\Ports\PageRepository;

class SearchCharacteristics
{
    private $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function execute(string $type, string $search):array
    {
        return $this->pageRepository->search($search);
    }
}
