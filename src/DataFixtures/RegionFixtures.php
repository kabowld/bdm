<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegionFixtures extends Fixture implements FixtureGroupInterface
{
    const LIST_REGIONS_CITIES = [
        'Lagunes' => ['Abidjan', 'Anyama', 'Dabou', 'Bingerville', 'Tiassale'],
        'Vallée du Bandama' => ['Bouaké', 'Katiola'],
        'Haut Sassandra' => ['Daloa', 'Issia', 'Vavoua'],
        'Lacs' => ['Yamoussoukro', 'Toumodi'],
        'Bas Sassandra' => ['San Pédro', 'Soubré'],
        'Sud Bandama' => ['Divo', 'Lakota'],
        'Savanes' => ['Korhogo','Ferkessedougou', 'Tingréla', 'Boundiali'],
        'Moyen Comoé' => ['Abengourou', 'Agnibilékrou', 'Bebou'],
        'Dix-huit Montagnes' => ['Man', 'Danané'],
        'Fromager' => ['Gagnoa', 'Oumé'],
        'Agnéby' => ['Agboville', 'Adzopé'],
        'Marahoué' => ['Bouaflé', 'Sinfra', 'Zuénoula'],
        'Sud Comoé' => ['Aboisso'],
        'Moyen Cavally' => ['Duékoué', 'Guiglo'],
        'N\'zi Comoé' => ['Dimbokro', 'Daoukro'],
        'Zanzan' => ['Bondoukou'],
        'Worodougou' => ['Séguéla'],
        'Bafing' => ['Koro', 'Touba'],
        'Denguélé' => ['Odienné' ],
    ];

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::LIST_REGIONS_CITIES as $item => $values) {
            // Set Regions
            $region = new Region();
            $region
                ->setTitle($item)
                ->setSlug($this->slugger->slug($item))
            ;
            $manager->persist($region);

            // Set cities and region linked
            foreach ($values as $value) {
                $city = new City();
                $city
                    ->setTitle($value)
                    ->setSlug($this->slugger->slug($value))
                    ->setRegion($region)
                ;
                $manager->persist($city);
            }
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['regions'];
    }
}
