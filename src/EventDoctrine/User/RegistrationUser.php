<?php

namespace App\EventDoctrine\User;

use App\Core\Utils;
use App\Service\SendMail;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationUser
{
    private const PATH_VERIFY_ACCOUNT = 'Email/verify_account.html.twig';
    const CREATE_ACCOUNT_SUBJECT = 'Création de compte';

    private $utils;
    private $sendMail;
    private $passwordHasher;

    public function __construct(SendMail $sendMail, UserPasswordHasherInterface $passwordHasher, Utils $utils)
    {
        $this->sendMail = $sendMail;
        $this->passwordHasher = $passwordHasher;
        $this->utils = $utils;
    }

    public function postPersist(User $user, LifecycleEventArgs $event): void
    {
        $this->setRole($user);
        $this->hashPassword($user);
        $event->getObjectManager()->flush();
        $this->sendVerifyMail($user);
    }

    /**
     * @param User $user
     */
    private function sendVerifyMail(User $user)
    {
        $url = sprintf('%s/validation/compte/%s', $this->utils->getUri(), $user->getConfirmatoken());
        $this->sendMail->verifyAccountUser(
            $user->getEmail(),
            self::CREATE_ACCOUNT_SUBJECT,
            self::PATH_VERIFY_ACCOUNT,
            ['url' => $url, 'subject' => self::CREATE_ACCOUNT_SUBJECT]
        );
    }

    /**
     * hashPassword
     *
     * @param User $user
     *
     * @return void
     */
    private function hashPassword(User $user): void
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
    }

    /**
     * Add Role User
     *
     * @param User $user
     *
     * @return void
     */
    private function setRole(User $user): void
    {
        ($user->getGroupe()->getRole() === 'ROLE_USER') ?
            $user->addRole('ROLE_USER'):
            $user->addRole('ROLE_PRO')
        ;
    }
}
