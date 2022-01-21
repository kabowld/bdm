<?php

namespace App\Controller\Front;

use App\Entity\AnnonceSearch;
use App\Form\AnnonceSearchType;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\AnnonceRepository;
use App\Repository\CityRepository;
use App\Repository\RubriqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    private $cityRepository;
    private $rubriqueRepository;
    private $annonceRepository;

    public function __construct(
        CityRepository $cityRepository,
        RubriqueRepository $rubriqueRepository,
        AnnonceRepository $annonceRepository,
    )
    {
        $this->cityRepository = $cityRepository;
        $this->rubriqueRepository = $rubriqueRepository;
        $this->annonceRepository = $annonceRepository;
    }

    /**
     * @Route("/annonces", name="list_search_annonces_bdmk")
     *
     * @param PaginatorInterface $paginator
     * @param Request            $request
     *
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new AnnonceSearch();
        $form = $this->createForm(AnnonceSearchType::class, $search, [
            'action' => $this->generateUrl('list_search_annonces_bdmk'),
            'attr' => ['class' => 'home1-advnc-search']
        ]);

        $form->handleRequest($request);

        $annonces = $paginator->paginate(
            $this->annonceRepository->findAllAnnonceQuery($search),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('Front/Annonce/list.html.twig', [
            'annonces' => $annonces,
            'form' => $form->createView(),
            'cities' => $this->cityRepository->getCitiesByOrderTitle(),
            'rubriques' => $this->rubriqueRepository->getAllRubriqueAndCategories()
        ]);
    }


    /**
     * @Route("/annonces/recherche", name="annonces_request_bdmk")
     */
    public function requestAnnonces(Request $request, PaginatorInterface $paginator)
    {
        $search = new AnnonceSearch();
        $form = $this->createForm(AnnonceSearchType::class, $search, [
            'action' => $this->generateUrl('list_search_annonces_bdmk'),
            'method' => 'POST',
            'attr' => ['class' => 'home1-advnc-search']
        ]);

        $annonces = $paginator->paginate(
            $this->annonceRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        if ($category = $request->query->get('category')) {
            $annonces = $paginator->paginate(
                $this->annonceRepository->getAnnoncesByCategorySlug($category),
                $request->query->getInt('page', 1),
                10
            );
        }

        if ($rubrique = $request->query->get('rubrique')) {
            $annonces = $paginator->paginate(
                $this->annonceRepository->getAnnoncesByRubriqueSlug($rubrique),
                $request->query->getInt('page', 1),
                10
            );
        }

        if ($city = $request->query->get('city')) {
            $annonces = $paginator->paginate(
                $this->annonceRepository->getAnnoncesByCitySlug($city),
                $request->query->getInt('page', 1),
                10
            );
        }

        if ($region = $request->query->get('region')) {
            $annonces = $paginator->paginate(
                $this->annonceRepository->getAnnoncesByRegionSlug($region),
                $request->query->getInt('page', 1),
                10
            );
        }


        return $this->render('Front/Annonce/list.html.twig', [
            'cities' => $this->cityRepository->getCitiesByOrderTitle(),
            'rubriques' => $this->rubriqueRepository->getAllRubriqueAndCategories(),
            'form' => $form->createView(),
            'annonces' => $annonces
        ]);
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
