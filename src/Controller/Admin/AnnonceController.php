<?php
declare(strict_types=1);

namespace App\Controller\Admin;


use App\Entity\Annonce;
use App\Entity\State;
use App\Form\AnnonceType;
use App\Manager\AnnonceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AnnonceController
 *
 * @Route("/admin/annonce")
 */
class AnnonceController extends AbstractController
{
    private AnnonceManager $annonceManager;

    public function __construct(AnnonceManager $annonceManager)
    {
        $this->annonceManager = $annonceManager;
    }

    /**
     * @Route("/liste", name="admin_annnonce_liste_bdmk", methods={"GET"})
     *
     * @return Response
     */
    public function liste(): Response
    {
        return $this->render(
            'Admin/Annonce/liste.html.twig',
            ['annonces' => $this->annonceManager->getMyAnnonces($this->getUser())]
        );
    }


    /**
     * @Route("/show/{id}", name="admin_annnonce_show_bdmk", methods={"GET"})
     *
     * @param Annonce $annonce
     *
     * @return Response
     */
    public function show(Annonce $annonce): Response
    {
        return $this->render(
            'Admin/Annonce/show.html.twig',
            ['annonce' => $this->annonceManager->getOnlyMyAnnonce($this->getUser(), $annonce->getId())]
        );
    }

    /**
     * @Route("/ajout", name="admin_annnonce_add_bdmk", methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request)
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setOwner($this->getUser());
            $this->annonceManager->persist($annonce);

            return $this->redirectToRoute('admin_annnonce_liste_bdmk');
        }

        return $this->render(
            'Admin/Annonce/new.html.twig', [
                'form' => $form->createView(),
                'states' => $this->annonceManager->all(State::class)
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="admin_annnonce_edit_bdmk", methods={"GET", "POST"}, requirements={"id" = "\d+"})
     *
     * @param Annonce $annonce
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Annonce $annonce, Request $request): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->annonceManager->persist($form->getData(), false);

            return $this->redirectToRoute('admin_annnonce_liste_bdmk');
        }

        return $this->render(
            'Admin/Annonce/edit.html.twig', [
                'form' => $form->createView(),
                'states' => $this->annonceManager->all(State::class),
                'annonce' => $annonce
            ]
        );
    }

    /**
     * Action Controller to get info on Rubrique by Ajax Request
     *
     * @Route(
     *     "/rubrique/image",
     *     name="admin_rubrique_img_bdmk",
     *     methods={"POST"},
     *     options={"expose" = true}
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getRubriqueImage(Request $request): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException(AnnonceManager::PAGE_NOT_FOUND);
        }

        return $this->annonceManager->getResponseRubriqueInfos($request->request->get('id'));
    }


    /**
     * Action Controller to get Pack infos by Ajax Request
     *
     * @Route(
     *     "/pack/infos",
     *     name="admin_pack_info_bdmk",
     *     methods={"POST"},
     *     options={"expose" = true}
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getPackInfos(Request $request): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException(AnnonceManager::PAGE_NOT_FOUND);
        }

        return $this->annonceManager->getResponsePackInfos($request->request->get('id'));
    }
}
