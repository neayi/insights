<?php


namespace App\Src\Insights\Insights\Application\Read\Context;


use App\Src\Insights\Insights\Domain\Ports\PageRepository;

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
