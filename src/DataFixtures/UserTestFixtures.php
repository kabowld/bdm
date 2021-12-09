<?php


namespace App\DataFixtures;


use App\Entity\Groupe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTestFixtures extends Fixture implements FixtureGroupInterface
{
    private $hashPassword;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->hashPassword = $userPasswordHasher;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $groupe = new Groupe();
        $groupe
            ->setTitle('Role test')
            ->setRole('ROLE_TEST')
            ;
        $user = new User();
        $user
            ->setEmail('test@mail.test')
            ->setPassword($this->hashPassword->hashPassword($user ,'P@ssw0rd'))
            ->addRole('ROLE_TEST')
            ->setGroupe($groupe)
        ;
        $manager->persist($user);
        $manager->flush();
    }


    public static function getGroups(): array
    {
        return ['user_test'];
    }
}
