<?php

namespace App\DataFixtures;

use App\Entity\DetailsPack;
use App\Entity\Pack;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class PackFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $packs = $this->getPack();
        $details = $this->getDetails();
        foreach ($packs as $item => $value) {
            $pack = new Pack();
            $pack
                ->setTitle($value['title'])
                ->setDescription($value['description'])
                ->setPrice($value['price'])
                ->setDays($value['days'])
                ->setPriceByDay($value['priceByDay'])
                ->setLevel($value['level'])
            ;
            $manager->persist($pack);

            $n = 0;
            while (true) {
                $detailsPack = new DetailsPack();
                $detailsPack
                    ->setDescription($details[$n])
                    ->setPack($pack)
                    ;
                $manager->persist($detailsPack);
                $n++;
                if ($n === 3) {
                    break;
                }
            }
        }

        $manager->flush();
    }

    /**
     * @return array[]
     */
    private function getPack(): array
    {
        return [
            [
                'title' => 'Tête de liste',
                'description' => 'Votre annonce sera placée en tête de liste automatiquement tous les jours pour une meilleure visibilité.',
                'price' => '1000',
                'days' => 7,
                'priceByDay' => '50 F cfa/Jour',
                'level' => 'medium'
            ],
            [
                'title' => 'Tête de liste + Star',
                'description' => 'Votre annonce sera placée en tête de liste automatiquement tous les jours tout étant en surbrillance avec un arrière-plan de couleur Jaune, pour une meilleur visibilité',
                'price' => '5000',
                'days' => 14,
                'priceByDay' => '100 F cfa/Jour',
                'level' => 'star'
            ],
            [
                'title' => 'Tête de liste + Premium',
                'description' => 'Affichez votre annonce dans la section Premium, un emplacement exclusif et sera placée en tête de liste automatiquement tous les jours',
                'price' => '10 000',
                'days' => 14,
                'priceByDay' => '100 F cfa/Jour',
                'level' => 'premium'
            ]
        ];
    }

    /**
     * @return array
     */
    private function getDetails(): array
    {
        return [
            'Phasellus sit amet nisl pulvinar, feugiat tortor non, porttitor tortor. Vestibulum ante ipsum primis in faucibus orci luctus.',
            'Duis at tortor vitae est lobortis feugiat. Quisque sed maximus mi. Ut dictum sapien turpis, sed finibus nisi facilisis sit amet.',
            'Maecenas volutpat elit odio. Aliquam et nibh porttitor, placerat metus quis, rutrum mauris. Nunc ut imperdiet augue. Sed in fermentum lorem.'
        ];
    }

    public static function getGroups(): array
    {
        return ['packs'];
    }
}
