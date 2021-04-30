<?php


namespace Tests\Integration\Repositories;


use App\Src\UseCases\Domain\Agricultural\Model\Page;
use Tests\TestCase;

class PageRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSavePage()
    {
        $page = new Page(1);
        $this->pageRepository->save($page);

        self::assertDatabaseHas('pages', ['page_id' => 1]);
    }

    /**
     * @test
     */
    public function shouldGetPage()
    {
        $page = new Page(1, true);
        $this->pageRepository->save($page);

        $expected = clone $page;
        $pageRetrieved = $this->pageRepository->get(1);
        self::assertEquals($expected, $pageRetrieved);
    }

    /**
     * @test
     */
    public function shouldUpdatePage()
    {
        $page = new Page(1, true);
        $this->pageRepository->save($page);

        $pageUpdated = new Page(1, false);
        $this->pageRepository->save($pageUpdated);

        $expected = clone $pageUpdated;
        $pageRetrieved = $this->pageRepository->get(1);
        self::assertEquals($expected, $pageRetrieved);
    }
}
