<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\HandlingUser;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessEventListener
{
    private $handlingUser;

    /**
     * LoginSuccessEventListener constructor.
     *
     * @param HandlingUser $handlingUser
     */
    public function __construct(HandlingUser $handlingUser)
    {
        $this->handlingUser = $handlingUser;
    }

    /**
     * @param LoginSuccessEvent $event
     */
    public function onSecurityLogin(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        if ($user instanceof User) {
            $this->handlingUser->updateLastLogin($user);
        }
    }
}
