<?php
declare(strict_types=1);

namespace App\Controller\Front;


use App\Repository\CityRepository;
use App\Repository\RubriqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{

    /**
     * Home Action Controller
     *
     * @Route("/", name="home_bdmk", methods={"GET"})
     *
     * @param RubriqueRepository $rubriqueRepository
     * @param CityRepository     $cityRepository
     *
     * @return Response
     */
    public function index(CityRepository $cityRepository, RubriqueRepository $rubriqueRepository): Response
    {
        return $this->render('Front/Pages/home.html.twig', [
            'cities' => $cityRepository->getCitiesByOrderTitle(),
            'rubriques' => $rubriqueRepository->getAllRubriqueAndCategories()
        ]);
    }

    /**
     * Contact Action Controller
     *
     * @Route("/contact", name="contact_bdmk", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function contact(): Response
    {
        return $this->render('Front/Pages/contact.html.twig');
    }

    /**
     * FAQs Action Controller
     *
     * @Route("/faq", name="faq_bdmk", methods={"GET"})
     *
     * @return Response
     */
    public function faq(): Response
    {
        return $this->render('Front/Pages/faq.html.twig');
    }

    /**
     * About Action Controller
     *
     * @Route("/qui-sommes-nous", name="about_bdmk", methods={"GET"})
     *
     * @return Response
     */
    public function about(): Response
    {
        return $this->render('Front/Pages/about.html.twig');
    }

    /**
     * Condition Action Controller
     *
     * @Route("/conditions-generales", name="conditions_bdmk", methods={"GET"})
     *
     * @return Response
     */
    public function conditionGenerale(): Response
    {
        return $this->render('Front/Pages/condition-general.html.twig');
    }


}
