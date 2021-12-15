<?php


namespace App\Tests\Entity;


use App\Entity\Annonce;
use App\Entity\City;
use App\Entity\Region;

class AnnonceTest extends EntityTestCase
{

    public function testBlankTitle()
    {
        $annonce = $this->getAnnonce();
        $annonce->setTitle('');

        $this->assertHasErrors($annonce, 2);
    }

    public function testWithBadLengthTitle()
    {
        $annonce = $this->getAnnonce();
        $annonce->setTitle($this->str_random(2));

        $this->assertHasErrors($annonce, 1);

        $annonce = $this->getAnnonce();
        $annonce->setTitle($this->str_random(200));

        $this->assertHasErrors($annonce, 1);
    }

    public function testWithGoodLengthTitle()
    {
        $annonce = $this->getAnnonce();
        $annonce->setTitle($this->str_random(3));

        $this->assertHasErrors($annonce);

        $annonce = $this->getAnnonce();
        $annonce->setTitle($this->str_random(150));

        $this->assertHasErrors($annonce);
    }

    public function testBlankDescription()
    {
        $annonce = $this->getAnnonce();
        $annonce->setDescription('');

        $this->assertHasErrors($annonce, 2);
    }

    public function testWithBadMinLengthDescription()
    {
        $annonce = $this->getAnnonce();
        $annonce->setTitle($this->str_random(2));

        $this->assertHasErrors($annonce, 1);
    }

    public function testWithGoodMinLengthDescription()
    {
        $annonce = $this->getAnnonce();
        $annonce->setDescription($this->str_random(5));

        $this->assertHasErrors($annonce);
    }

    public function testWithBlankType()
    {
        $annonce = $this->getAnnonce();
        $annonce->setType('');

        $this->assertHasErrors($annonce, 2);
    }

    public function testTypeByAnnonce()
    {
        $annonce = $this->getAnnonce();

        $annonce->setType('type 1');
        $this->assertHasErrors($annonce, 1);


        $annonce->setType('vente en ligne');
        $this->assertHasErrors($annonce, 1);

        $annonce->setType('offre');
        $this->assertHasErrors($annonce);


        $annonce->setType('demande');
        $this->assertHasErrors($annonce);
    }

    private function getAnnonce(): Annonce
    {
        $region = (new Region())->setTitle('Region')->setSlug('region');
        $ville = (new City())->setTitle('Ville')->setSlug('ville');

        return (new Annonce())
            ->setTitle('title annonce')
            ->setDescription('description')
            ->setLocation('check adresss')
            ->setPostalCode('01 BP 10605 ABIDJAN 01')
            ->setCity($ville)
            ->setRegion($region)
            ->setType('offre')
            ;



    }
}
