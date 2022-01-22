<?php

namespace App\Controller\Front;

use App\Entity\AnnonceSearch;
use App\Form\AnnonceSearchType;
use App\Manager\AnnonceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    private $annonceManager;

    public function __construct(AnnonceManager $annonceManager)
    {
        $this->annonceManager = $annonceManager;
    }

    /**
     * @Route("/annonces", name="list_search_annonces_bdmk", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $search = new AnnonceSearch();
        $form = $this->createForm(AnnonceSearchType::class, $search);
        $form->handleRequest($request);

        return $this->render(
            'Front/Annonce/list.html.twig',
            array_merge(
                $this->annonceManager->renderSearchParameters($form),
                ['annonces' => $this->annonceManager->getAnnoncesByQuerySearch($request, $search)]
            )
        );
    }


    /**
     * @Route("/annonces/recherche", name="annonces_request_bdmk", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function requestPropertyAnnonces(Request $request): Response
    {
        $search = new AnnonceSearch();
        $form = $this->createForm(AnnonceSearchType::class, $search);
        $form->handleRequest($request);

        return $this->render(
            'Front/Annonce/list.html.twig',
            array_merge(
                $this->annonceManager->renderSearchParameters($form),
                ['annonces' => $this->annonceManager->getAnnoncesByProperty($request)]
            )
        );
    }

    /**
     * @Route("/annonce/show", name="annonce_single_bdmk", methods={"GET"})
     *
     * @return Response
     */
    public function show(): Response
    {
        return $this->render('Front/Annonce/show.html.twig');
    }
}
