<?php
declare(strict_types=1);

namespace App\EventListener;


use App\Entity\User;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticationSuccessListener
{
    private const DEACTIVATED_ACCOUNT_EXCEPTION = 'Votre compte n\'est pas activé !';

    /**
     * Authentication success only if account activated
     *
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessEvent(AuthenticationSuccessEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $user = $event->getAuthenticationToken()->getUser();

        if (!$user instanceof User && $token->isAuthenticated()) {
            return;
        }

        // Locked out user if account disabled
        if (!$user->isEnabled()) {
            throw new AuthenticationException(self::DEACTIVATED_ACCOUNT_EXCEPTION);
        }
    }
}
