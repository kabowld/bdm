<?php

namespace App\Controller\Admin;

use App\Manager\AnnonceManager;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/favoris")
 */
class FavorisController extends AbstractController
{
    const ANNONCE_MESS_REMOVE = 'Annonce retirée des favoris';
    const ANNONCE_MESS_ADD = 'Annonce ajoutée aux favoris';

    private $em;
    private $annonceRepository;

    /**
     * @param EntityManagerInterface $em
     * @param AnnonceRepository      $annonceRepository
     */
    public function __construct(EntityManagerInterface $em, AnnonceRepository $annonceRepository)
    {
        $this->em = $em;
        $this->annonceRepository = $annonceRepository;
    }

    /**
     * @Route("/liste", name="admin_favoris_liste_bdmk", methods={"GET", "POST"})
     *
     * @param Request            $request
     * @param PaginatorInterface $paginator
     * @param AnnonceRepository  $annonceRepository
     *
     * @return Response
     */
    public function listFavoris(Request $request, PaginatorInterface $paginator, AnnonceRepository $annonceRepository)
    {
        $favoris = $paginator->paginate(
            $annonceRepository->getFavoris($this->getUser()),
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('Admin/Favoris/list.html.twig', ['favoris' => $favoris]);
    }

    /**
     * @Route(
     *     "/disable",
     *     name="admin_disable_favoris_bdmk",
     *     methods={"POST"},
     *     options={"expose" = true}
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function disable(Request $request): JsonResponse
    {
        return $this->suscribe($request, self::ANNONCE_MESS_REMOVE);
    }

    /**
     * @Route(
     *     "/enable",
     *     name="admin_enable_favoris_bdmk",
     *     methods={"POST"},
     *     options={"expose" = true}
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function enable(Request $request): JsonResponse
    {
        return $this->suscribe($request, self::ANNONCE_MESS_ADD);
    }

    /**
     * @param Request $request
     * @param string  $message
     *
     * @return JsonResponse
     */
    private function suscribe(Request $request, string $message): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException(AnnonceManager::PAGE_NOT_FOUND);
        }

        $id = $request->request->get('annonceId');
        if (!$annonce = $this->annonceRepository->find($id)) {
            return new JsonResponse(
                ['status' => 'fail', 'message' => 'Aucune annone'],
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }

        $request->request->get('action') === 'disable'?
            $this->getUser()->removeFavori($annonce):
            $this->getUser()->addFavori($annonce)
        ;

        $this->em->flush();

        return new JsonResponse(
            ['status' => 'success', 'message' => $message],
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}
