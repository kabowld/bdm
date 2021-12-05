<?php
declare(strict_types=1);

namespace App\Controller\Security;


use App\Service\HandlingUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResettingController extends AbstractController
{
    /**
     * @Route("/reset-password", name="reset_bdmk", methods={"GET", "POST"})
     *
     * @param HandlingUser $handlingUser
     *
     * @return Response
     */
    public function forgetPassword(HandlingUser $handlingUser): Response
    {
        $handlingUser->redirectUserIfLogged();

        return $this->render('Security/Reset/recovery_password.html.twig');
    }
}
