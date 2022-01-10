<?php
declare(strict_types=1);

namespace App\Controller\Admin;


use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\State;
use App\Form\AnnonceType;
use App\Manager\AnnonceManager;
use App\Repository\PackRepository;
use App\Repository\RubriqueRepository;
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

            $this->addFlash('info', 'Votre annonce vient d\être ajoutée avec succès !');
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

            $this->addFlash('info', 'Votre annonce vient d\être modifiée avec succès !');
            return $this->redirectToRoute('admin_annnonce_liste_bdmk');
        }

        return $this->render(
            'Admin/Annonce/edit.html.twig', [
                'form' => $form->createView(),
                'states' => $this->annonceManager->all(State::class),
            ]
        );
    }

    /**
     * @param Request            $request
     * @param RubriqueRepository $rubriqueRepository
     *
     * @return JsonResponse
     *
     * @Route("/rubrique/find/image", name="admin_rubrique_img_bdmk", methods={"POST"}, options={"expose" = true})
     */
    public function getRubriqueImage(Request $request, RubriqueRepository $rubriqueRepository)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('La page est introuvable !');
        }

        $rubrique = $rubriqueRepository->find($request->request->get('id'));
        if (!$rubrique) {
            return new JsonResponse(['message' => 'error'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(
            ['message' => 'success', 'filename' => $rubrique->getImage()->getFileName(), 'slug' => $rubrique->getSlug()],
        Response::HTTP_OK,
        ['Content-Type' => 'application/json']
        );
    }
}
