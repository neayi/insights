<?php


namespace Tests\Unit\System;


use App\Src\Insights\Insights\Application\UseCase\Interactions\SetPageDryState;
use App\Src\Insights\Insights\Domain\Interactions\Page;
use Tests\TestCase;

class SetPageDryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSetPageOnDryState()
    {
        $page = new Page(1);
        $this->pageRepository->save($page);

        app(SetPageDryState::class)->execute(1);

        $pageExpected = new Page(1, true);
        $pageSaved = $this->pageRepository->get(1);
        self::assertEquals($pageExpected, $pageSaved);
    }
}
