<?php

namespace App\DataFixtures;

use App\Entity\Groupe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GroupeFixtures extends Fixture implements FixtureGroupInterface
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

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

            if ($groupe->getRole() === 'ROLE_USER') {
                $user = $this->loadUser($groupe);
                $manager->persist($user);
            }

            $manager->persist($groupe);
        }

        $manager->flush();
    }

    private function loadUser(Groupe $groupe)
    {
        $user = new User();
        $user
            ->setEmail('dev@mail.test')
            ->setPassword($this->userPasswordHasher->hashPassword($user, 'P@ssw0rd'))
            ->setConfirmatoken('36f4fffbc8d978a4bab1fe3c1a144093e9c6f3ac4a0bd0fef8ccaa8806999030e88f8081bf96d92d4351c8c9ed955e40222325f482f4a53c396d5348ed52a71d590ab3b24f513e9a2ec695aba0bf89278e64dd3b6b8a964550c1d0004612173a46a3a2ba91ca8079c296fd991154b876cff8a793654cda58')
            ->setEnabled(true)
            ->setIsVerified(true)
            ->setGroupe($groupe)
            ->addRole($groupe->getRole())
        ;

        return $user;
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
