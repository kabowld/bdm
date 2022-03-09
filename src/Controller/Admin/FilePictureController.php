<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use App\Manager\AnnonceManager;
use App\Repository\FilePictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilePictureController extends AbstractController
{
    /**
     * @Route("/delete/filepicture", name="admin_annonce_del_filepicture", options={"expose" = true})
     */
    public function deleteFilePicture(Request $request, EntityManagerInterface $em, FilePictureRepository $filePictureRepository)
    {
        if ($request->isXmlHttpRequest()) {
            throw $this->createNotFoundException(AnnonceManager::PAGE_NOT_FOUND);
        }
        $id = $request->request->get('id');
        $picture = $filePictureRepository->find($id);
        if (!$picture) {
            return new JsonResponse(['status' => 'fail'], Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        $em->remove($picture);
        $em->flush();

        return new JsonResponse(['status' => 'success'], Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
