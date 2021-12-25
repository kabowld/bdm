<?php


namespace App\Tests\Entity;


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

    public function testBadLengthTitle()
    {
        $state = new State();
        $state
            ->setTitle($this->str_random(51))
            ->setDescription('Description état')
        ;
        $this->assertHasErrors($state, 1);
    }

    public function testGoodLengthTitle()
    {
        $state = new State();
        $state
            ->setTitle($this->str_random(50))
            ->setDescription('Description état')
        ;
        $this->assertHasErrors($state);
    }

    public function testBlankTitleAndDescription()
    {
        $state = new State();
        $state
            ->setTitle('')
            ->setDescription('')
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
        ;
        $this->assertHasErrors($state);
    }


}
