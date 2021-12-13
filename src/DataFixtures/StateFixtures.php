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
        $states = ['Mauvais état', 'Etat correct', 'Bon état', 'Très bon état', 'Neuf'];

        foreach($states as $title) {
            $state = new State();
            $state->setTitle($title);
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
