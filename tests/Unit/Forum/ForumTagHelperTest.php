<?php


namespace Tests\Unit\Forum;

use App\Src\UseCases\Domain\Forum\ForumTagHelper;
use Tests\TestCase;

class ForumTagHelperTest extends TestCase
{
    /**
     * @test
     * @dataProvider tagNameProvider
     */
    public function shouldSanitizeTagNames(string $rawTagName, string $expectedTagName)
    {
        self::assertEquals($expectedTagName, ForumTagHelper::sanitizeTagName($rawTagName));
    }

    private function tagNameProvider(): array
    {
        return [
            ['Aviculture (oeufs)', 'Aviculture-oeufs'],
            ['100% plein air', '100-plein-air'],
            ['Fertilisation azotée avec la méthode Appi-N', 'Fertilisation-azotée-avec-la-méthode-Appi-N'],
        ];
    }
}
