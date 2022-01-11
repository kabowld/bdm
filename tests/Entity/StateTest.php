<?php


namespace App\Tests\Entity;


use App\Entity\CategoryState;
use App\Entity\State;

class StateTest extends EntityTestCase
{
    public function addTitleProviderState(): array
    {
        return [
            ['Etat satisfaisant'],
            ['Etat correct'],
            ['Bon état'],
            ['Très bon état'],
            ['Neuf'],
        ];
    }

    public function bestRangeStars(): array
    {
        return [
            [5], [4], [3], [2],  [1]
        ];
    }

    public function testBadLengthTitle()
    {
        $state = new State();
        $state
            ->setTitle($this->str_random(51))
            ->setDescription('Description état')
            ->setCategoryState($this->getCategoryState())
        ;
        $this->assertHasErrors($state, 1);
    }

    public function testGoodLengthTitle()
    {
        $state = new State();
        $state
            ->setTitle($this->str_random(50))
            ->setDescription('Description état')
            ->setCategoryState($this->getCategoryState())
        ;
        $this->assertHasErrors($state);
    }

    public function testBlankTitleAndDescription()
    {
        $state = new State();
        $state
            ->setTitle('')
            ->setDescription('')
            ->setCategoryState($this->getCategoryState())
        ;
        $this->assertHasErrors($state, 2);
    }

    /**
     * @dataProvider addTitleProviderState
     */
    public function testTitleWithGoodTitle($value)
    {
        $state = new State();
        $state
            ->setTitle($value)
            ->setDescription('description etat')
            ->setCategoryState($this->getCategoryState())
        ;
        $this->assertHasErrors($state);
    }


    public function testWithExpectedMaxLengthStars()
    {
        $state = new State();
        $state
            ->setTitle('state title')
            ->setDescription('description etat')
            ->setStars(6)
            ->setCategoryState($this->getCategoryState())
        ;
        $this->assertHasErrors($state, 1);
    }

    /**
     * @dataProvider bestRangeStars
     */
    public function testStarInRangeLength(int $value)
    {
        $state = new State();
        $state
            ->setTitle('state title')
            ->setDescription('description etat')
            ->setStars($value)
            ->setCategoryState($this->getCategoryState())
        ;
        $this->assertHasErrors($state);
    }

    /**
     * @return CategoryState
     */
    private function getCategoryState(): CategoryState
    {
        return (new CategoryState())->setTitle('normal');
    }

}
