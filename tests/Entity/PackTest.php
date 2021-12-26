<?php

namespace App\Tests\Entity;

use App\Entity\Pack;

class PackTest extends EntityTestCase
{
    public function testTitleFailAssert()
    {
        $pack = $this->getPack();
        $pack->setTitle('');
        $this->assertHasErrors($pack, 1);

        $pack->setTitle($this->str_random(101));
        $this->assertHasErrors($pack, 1);
    }

    public function testDescriptionNotBlankAssert()
    {
        $pack = $this->getPack();
        $pack->setDescription('');
        $this->assertHasErrors($pack, 1);
    }

    public function testPriceFailAssert()
    {
        $pack = $this->getPack();
        $pack->setPrice('');
        $this->assertHasErrors($pack, 1);

        $pack->setTitle($this->str_random(11));
        $this->assertHasErrors($pack, 1);
    }

    public function testDaysFailAssert()
    {
        $pack = $this->getPack();

        $pack->setDays(0);
        $this->assertHasErrors($pack, 1);
    }

    public function testPriceByDayFailAssert()
    {
        $pack = $this->getPack();
        $pack->setPriceByDay('');
        $this->assertHasErrors($pack, 1);

        $pack->setPriceByDay($this->str_random(51));
        $this->assertHasErrors($pack, 1);
    }

    protected function getPack(): Pack
    {
        return (new Pack())
            ->setTitle('Pack premium')
            ->setDays(10)
            ->setDescription('Pack premium disponible pour vous')
            ->setPrice('150 000')
            ->setPriceByDay('10 Frcs/Jour');
    }
}
