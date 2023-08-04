<?php

declare(strict_types=1);

namespace App\Src\UseCases\Domain\Context\Queries;


use App\Src\UseCases\Domain\Ports\PageRepository;

/**
 * Search characteristics in the wiki pages
 */
class SearchCharacteristics
{
    public function __construct(
        private PageRepository $pageRepository
    ){}

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
