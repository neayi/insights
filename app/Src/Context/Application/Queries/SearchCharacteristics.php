<?php


namespace App\Src\Context\Application\Queries;


use App\Src\Context\Domain\PageRepository;

/**
 * Search characteristics in the wiki pages
 */
class SearchCharacteristics
{
    private $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function execute(string $type, string $search):array
    {
        if($type === 'farming') {
            $type = 'culture';
        }
        if($type === 'croppingSystem') {
            $type = 'label';
        }
        return $this->pageRepository->search($type, $search);
    }
}
