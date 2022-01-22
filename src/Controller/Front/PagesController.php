<?php
declare(strict_types=1);

namespace App\Controller\Front;


use App\Entity\AnnonceSearch;
use App\Form\AnnonceSearchType;
use App\Repository\CityRepository;
use App\Repository\RubriqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{

    /**
     * Home Action Controller
     *
     * @Route("/", name="home_bdmk", methods={"GET"})
     *
     * @param Request            $request
     * @param CityRepository     $cityRepository
     * @param RubriqueRepository $rubriqueRepository
     *
     * @return Response
     */
    public function index(Request $request, CityRepository $cityRepository, RubriqueRepository $rubriqueRepository): Response
    {
        $search = new AnnonceSearch();
        $form = $this->createForm(AnnonceSearchType::class, $search);
        $form->handleRequest($request);

        return $this->render('Front/Pages/home.html.twig', [
            'form' => $form->createView(),
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
     * @Route("/conditions-generales", name="cgu_bdmk", methods={"GET"})
     *
     * @return Response
     */
    public function cgu(): Response
    {
        return $this->render('Front/Pages/condition-general.html.twig');
    }


}
