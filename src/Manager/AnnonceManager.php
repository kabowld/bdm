<?php
declare(strict_types=1);

namespace App\Manager;


use App\Entity\Annonce;
use App\Entity\AnnonceSearch;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\Pack;
use App\Entity\Rubrique;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class AnnonceManager extends Manager
{
    public const PAGE_NOT_FOUND = 'La page est introuvable !';

    /**
     * Returns annonces by owner
     *
     * @param UserInterface $owner
     *
     * @return array
     */
    public function getMyAnnonces(UserInterface $owner): array
    {
        return $this->em->getRepository(Annonce::class)->getAnnoncesByOwner($owner);
    }

    /**
     * @param mixed $id
     *
     * @return JsonResponse
     */
    public function getResponsePackInfos($id): JsonResponse
    {
        $pack = $this->find(Pack::class, $id);
        if (!$pack) {
            return $this->sendErrorResponse();
        }

        /** @var Pack $pack */
        return new JsonResponse([
            'message' => 'success',
            'filename' => $pack->getImage()->getFileName(),
            'title' => $pack->getTitle(),
            'description' => $pack->getDescription(),
            'price' => $pack->getPrice(),
            'priceByDays' => $pack->getPriceByDay()
        ], Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @param mixed $id
     *
     * @return JsonResponse
     */
    public function getResponseRubriqueInfos($id): JsonResponse
    {
        $rubrique = $this->find(Rubrique::class, $id);
        if (!$rubrique) {
            return $this->sendErrorResponse();
        }

        return new JsonResponse([
            'message' => 'success',
            'filename' => $rubrique->getImage() ? $rubrique->getImage()->getFileName(): null,
            'slug' => $rubrique->getSlug()
        ], Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


    /**
     * @return JsonResponse
     */
    private function sendErrorResponse(): JsonResponse
    {
        return new JsonResponse(['message' => 'error'], Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
    }

}
