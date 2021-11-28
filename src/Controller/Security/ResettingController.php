<?php
declare(strict_types=1);

namespace App\Controller\Security;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResettingController extends AbstractController
{
    /**
     * @Route("/reset-password", name="reset_bdmk", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function forgetPassword()
    {
        return $this->render('Security/Reset/forgetpassword.html.twig');
    }
}
