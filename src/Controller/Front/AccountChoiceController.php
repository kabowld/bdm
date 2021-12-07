<?php
declare(strict_types=1);

namespace App\Controller\Front;


use App\Service\HandlingUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountChoiceController extends AbstractController
{
    /**
     * @Route("/choix/compte", name="choice_registration_bdmk", methods={"GET"})
     *
     * @param HandlingUser $handlingUser
     *
     * @return Response
     */
    public function choiceRegistration(HandlingUser $handlingUser): Response
    {
        $handlingUser->redirectUserIfLogged();

        return $this->render('Security/choice_registration.html.twig');
    }
}
