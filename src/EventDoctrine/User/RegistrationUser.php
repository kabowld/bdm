<?php

namespace App\EventDoctrine\User;

use App\Service\SendMail;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\User;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationUser
{
    use LoggerAwareTrait;
    private const PATH_VERIFY_ACCOUNT = 'Email/verify_account.html.twig';
    private const CREATE_ACCOUNT_SUBJECT = 'Création de compte';

    private $sendMail;
    private $passwordHasher;
    private $params;
    private $localUrl;

    public function __construct(SendMail $sendMail, UserPasswordHasherInterface $passwordHasher, ContainerBagInterface $params, string $localUrl)
    {
        $this->sendMail = $sendMail;
        $this->passwordHasher = $passwordHasher;
        $this->params = $params;
        $this->localUrl  = $localUrl;
    }

    /**
     * @param User               $user
     * @param LifecycleEventArgs $event
     */
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
        $url = sprintf('%s/validation/compte/%s', $this->getAppUrl(), $user->getConfirmatoken());
        $this->sendMail->createEmail(
            $user->getEmail(),
            self::CREATE_ACCOUNT_SUBJECT,
            self::PATH_VERIFY_ACCOUNT,
            ['url' => $url, 'localUrl'=> $this->localUrl]
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

    /**
     * @return string|null
     */
    private function getAppUrl(): ?string
    {
        try {
            return $this->params->get('app.url_local');
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            $this->logger->error($e, ['exception' => $e]);
        }

        return null;
    }
}
