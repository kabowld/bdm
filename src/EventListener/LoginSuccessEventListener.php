<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\HandlingUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessEventListener
{
    private const DASHBOARD_PATH = 'admin_dashboard_bdmk';

    private HandlingUser $handlingUser;
    private UrlGeneratorInterface $urlGenerator;

    /**
     * LoginSuccessEventListener constructor.
     *
     * @param HandlingUser          $handlingUser
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(HandlingUser $handlingUser, UrlGeneratorInterface $urlGenerator)
    {
        $this->handlingUser = $handlingUser;
        $this->urlGenerator = $urlGenerator;
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

        $response = new RedirectResponse($this->urlGenerator->generate(self::DASHBOARD_PATH));
        $event->setResponse($response);
    }


}
