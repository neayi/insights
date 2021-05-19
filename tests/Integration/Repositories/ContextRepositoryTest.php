<?php


namespace Tests\Integration\Repositories;


use App\Src\UseCases\Domain\Context\Model\Context;
use App\Src\UseCases\Domain\User;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use Tests\TestCase;

class ContextRepositoryTest extends TestCase
{
    public function testUpdateContext()
    {
        $characteristic1 = new CharacteristicsModel();
        $characteristic1->fill([
            'uuid' => 'abc',
            'main' => true,
            'priority' => 0,
            'icon' => '',
            'page_label' => 'label',
            'pretty_page_label' => 'label pretty',
            'page_id' => 1,
            'type' => 'prod',
            'code' => uniqid(),
        ]);

        $characteristic2 = new CharacteristicsModel();
        $characteristic2->fill([
            'uuid' => 'cdf',
            'main' => true,
            'priority' => 0,
            'icon' => '',
            'page_label' => 'label',
            'pretty_page_label' => 'label pretty',
            'page_id' => 2,
            'type' => 'prod',
            'code' => uniqid(),
        ]);

        $characteristic3 = new CharacteristicsModel();
        $characteristic3->fill([
            'uuid' => 'bcd',
            'main' => true,
            'priority' => 0,
            'icon' => '',
            'page_label' => 'label',
            'pretty_page_label' => 'label pretty',
            'page_id' => 3,
            'type' => 'prod',
            'code' => uniqid(),
        ]);
        $characteristic1->save();
        $characteristic2->save();
        $characteristic3->save();


        $user = new User('abc', 'g@gmail.com', 'f', 'l');
        $this->userRepository->add($user);

        $context = new Context('abc', '83220', ['abc', 'bcd', 'cdf'], '');
        $this->contextRepository->add($context, 'abc');

        $newContext = new Context('abc', '83130', ['abc', 'cdf'], 'test', 'sector', 'structure');
        $this->contextRepository->update($newContext, 'abc');

        $contextSaved = $this->contextRepository->getByUser('abc');
        self::assertEquals($newContext, $contextSaved);
    }
}
