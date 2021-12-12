<?php
declare(strict_types=1);

namespace App\Controller\Front;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountChoiceController extends AbstractController
{
    /**
     * @Route("/choix/compte", name="choice_registration_bdmk", methods={"GET"})
     *
     * @return Response
     */
    public function choiceRegistration(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin_dashboard_bdmk');
        }

        return $this->render('Front/Pages/choice_registration.html.twig');
    }
}
