<?php
declare(strict_types=1);

namespace App\EventListener;


use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticationSuccessListener
{
    private const DEACTIVATED_ACCOUNT_EXCEPTION = 'Votre compte n\'est pas activé !';
    private $em;

    /**
     * AuthenticationSuccessListener constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Authentication success only if account activated
     *
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessEvent(AuthenticationSuccessEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $user = $event->getAuthenticationToken()->getUser();

        if (!$user instanceof User && $token->getUser()) {
            return;
        }

        // Locked out user if account disabled
        if (!$user->isEnabled()) {
            throw new AuthenticationException(self::DEACTIVATED_ACCOUNT_EXCEPTION);
        }

        $user->setLastLogin(new \DateTimeImmutable());
        $this->em->flush();
    }
}
