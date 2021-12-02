<?php

namespace App\DataFixtures;

use App\Entity\Groupe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class GroupeFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * Load data for Groupe entity
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $tab = $this->rolesList();
        for ($i = 0; $i < 4; $i++) {
            $groupe = new Groupe();
            $groupe
                ->setTitle($tab['title'][$i])
                ->setRole($tab['role'][$i]);
            $manager->persist($groupe);
        }

        $manager->flush();
    }

    /**
     * List of roles and title
     *
     * @return array<string, array<int, string>>
     */
    private function rolesList(): array
    {
        return [
            'title' => ['SuperAdmin', 'Admin', 'Professionel', 'User'],
            'role' => ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_PRO', 'ROLE_USER']
        ];
    }

    /**
     * Specific group for this fixture
     *
     * @return array|string[]
     */
    public static function getGroups(): array
    {
        return ['groupe'];
    }
}
