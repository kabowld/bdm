<?php


namespace App\DataFixtures;


use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class StateFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $states = [
            'Etat satisfaisant' => 'Bien en état de fonctionnement correct, comportant des défauts et signes d’usure manifestes (mentionnés dans l’annonce et visibles sur les photos).',
            'Etat correct' => 'Bon état',
            'Bon état' => 'Bien en parfait état de fonctionnement, comportant quelques petits défauts (mentionnés dans l’annonce et visibles sur les photos).',
            'Très bon état' => 'Bien pas ou peu utilisé, sans aucun défaut ni rayure, complet et en parfait état de fonctionnement.',
            'Neuf' => 'Bien non-utilisé, complet, avec emballage non ouvert et notice(s) d’utilisation.'
        ];

        foreach($states as $title => $description) {
            $state = new State();
            $state
                ->setTitle($title)
                ->setDescription($description)
            ;
            $manager->persist($state);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public static function getGroups(): array
    {
        return ['state'];
    }
}
