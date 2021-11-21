<?php
declare(strict_types=1);

namespace App\Controller\Front;


use App\Repository\CityRepository;
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
    public function index(CityRepository $cityRepository): Response
    {
        $cities = $cityRepository->findAll();
        return $this->render('Front/Pages/home.html.twig', compact('cities'));
    }

    /**
     * @Route("/contact", name="contact_bdmk")
     *
     * @return Response
     */
    public function contact()
    {
        return $this->render('Front/Pages/contact.html.twig');
    }
}
