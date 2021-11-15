<?php
declare(strict_types=1);

namespace App\Controller\Front;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="home_bdmk")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('Front/Pages/home.html.twig');
    }

}
