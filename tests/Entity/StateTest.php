<?php


namespace App\Tests\Entity;


use App\Entity\State;

class StateTest extends EntityTestCase
{
    public function addTitleProviderState(): array
    {
        return [
            ['Mauvais état'],
            ['Etat correct'],
            ['Bon état'],
            ['Très bon état'],
            ['Neuf'],
        ];
    }

    public function testBadLengthTitle()
    {
        $state = new State();
        $state->setTitle($this->str_random(51));
        $this->assertHasErrors($state, 1);
    }

    public function testGoodLengthTitle()
    {
        $state = new State();
        $state->setTitle($this->str_random(50));
        $this->assertHasErrors($state);
    }

    public function testBlankTitle()
    {
        $state = new State();
        $state->setTitle('');
        $this->assertHasErrors($state, 1);
    }

    /**
     * @dataProvider addTitleProviderState
     */
    public function testTitleWithGoodTitle($value)
    {
        $state = new State();
        $state->setTitle($value);
        $this->assertHasErrors($state);
    }
}
