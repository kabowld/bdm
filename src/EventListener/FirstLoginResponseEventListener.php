<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class FirstLoginResponseEventListener
{
    private $security;
    private $urlGenerator;

    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator)
    {
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $user = $this->security->getUser();

        if (!$event->isMainRequest()) {
            return;
        }

        if (!$user instanceof User) {
            return;
        }

        if (!$user->getFisrtLogin()) {
            return;
        }

        if ($user->hasRole(User::ROLES['user'])) {
            $route = 'edit_profile_admin_bdmk';
        } elseif ($user->hasRole(User::ROLES['pro'])) {
            $route = 'edit_profile_pro_admin_bdmk';
        }

        if ($event->getRequest()->get('_route') === $route) {
            return;
        }

        $event->setResponse(new RedirectResponse($this->urlGenerator->generate($route)));
    }
}
