<?php

namespace App\Tests\Entity;

use App\Entity\DetailsPack;

class DetailsPackTest extends PackTest
{
    public function testDescriptionNotBlank()
    {
        $detailsPack = $this->getDetailsPack();
        $detailsPack->setDescription('');

        $this->assertHasErrors($detailsPack, 1);
    }

    private function getDetailsPack(): DetailsPack
    {
        return (new DetailsPack())
            ->setDescription('Info sur les différents packs')
            ->setPack($this->getPack());
    }
}
