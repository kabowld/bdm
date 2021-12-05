<?php
declare(strict_types=1);

namespace App\Controller\Security;


use App\Service\HandlingUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * Login Action
     *
     * @Route("/se-connecter", name="login_bdmk", methods={"GET", "POST"})
     *
     * @param AuthenticationUtils $authenticationUtils
     * @param HandlingUser        $user
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, HandlingUser $user): Response
    {
        $user->redirectUserIfLogged();
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

}
