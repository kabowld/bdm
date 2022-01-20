<?php

namespace App\Controller\Front;

use App\Entity\Annonce;
use App\Entity\AnnonceSearch;
use App\Entity\City;
use App\Entity\Rubrique;
use App\Form\AnnonceSearchType;
use App\Manager\AnnonceManager;
use App\Repository\AnnonceRepository;
use App\Repository\CityRepository;
use App\Repository\RubriqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{

    /**
     * @Route("/liste/annonces", name="list_search_annonces_bdmk")
     *
     * @param CityRepository $cityRepository
     * @param RubriqueRepository $rubriqueRepository
     * @param Request $request
     *
     * @return Response
     */
    public function index(CityRepository $cityRepository, RubriqueRepository $rubriqueRepository): Response
    {
        $search = new AnnonceSearch();
        $form = $this->createForm(AnnonceSearchType::class, $search, [
            'action' => $this->generateUrl('list_search_annonces_bdmk'),
            'method' => 'POST',
            'attr' => ['class' => 'home1-advnc-search']
        ]);

        return $this->render('Front/Annonce/list.html.twig', [
            'cities' => $cityRepository->getCitiesByOrderTitle(),
            'rubriques' => $rubriqueRepository->getAllRubriqueAndCategories(),
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/annonces", name="annonces_request_bdmk")
     */
    public function requestAnnonces(CityRepository $cityRepository, RubriqueRepository $rubriqueRepository)
    {
        $search = new AnnonceSearch();
        $form = $this->createForm(AnnonceSearchType::class, $search, [
            'action' => $this->generateUrl('list_search_annonces_bdmk'),
            'method' => 'POST',
            'attr' => ['class' => 'home1-advnc-search']
        ]);

       /* $result = $annonceRepository->findAll();
        if ($category = $request->query->get('category')) {
            $result = $annonceRepository->getAnnoncesByCategorySlug($category);
        }

        if ($rubrique = $request->query->get('rubrique')) {
            $result = $annonceRepository->getAnnoncesByRubriqueSlug($rubrique);
        }

        if ($city = $request->query->get('city')) {
            $result = $annonceRepository->getAnnoncesByCitySlug($city);
        }

        if ($region = $request->query->get('region')) {

            $result = $annonceRepository->getAnnoncesByRegionSlug($region);
        }*/


        return $this->render('Front/Annonce/list.html.twig', [
            'cities' => $cityRepository->getCitiesByOrderTitle(),
            'rubriques' => $rubriqueRepository->getAllRubriqueAndCategories(),
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/show", name="annonce_single_bdmk", methods={"GET"})
     *
     * @return Response
     */
    public function show(): Response
    {
        return $this->render('Front/Annonce/show.html.twig');
    }
}
