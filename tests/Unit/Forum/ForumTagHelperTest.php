<?php


namespace Tests\Unit\Forum;

use App\Src\UseCases\Domain\Forum\ForumTagHelper;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class ForumTagHelperTest extends TestCase
{
    #[Test]
    #[DataProvider('tagNameProvider')]
    public function shouldSanitizeTagNames(string $rawTagName, string $expectedTagName)
    {
        self::assertEquals($expectedTagName, ForumTagHelper::sanitizeTagName($rawTagName));
    }

    public static function tagNameProvider(): array
    {
        return [
            ['Aviculture (oeufs)', 'Aviculture-oeufs'],
            ['100% plein air', '100-plein-air'],
            ['Fertilisation azotée avec la méthode Appi-N', 'Fertilisation-azotée-avec-la-méthode-Appi-N'],
        ];
    }
}
