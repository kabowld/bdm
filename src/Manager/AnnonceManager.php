<?php
declare(strict_types=1);

namespace App\Manager;


use App\Entity\Annonce;
use App\Entity\AnnonceSearch;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\Pack;
use App\Entity\Rubrique;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class AnnonceManager extends Manager
{
    public const PAGE_NOT_FOUND = 'La page est introuvable !';
    private const ITEMS_BY_PAGE = 30;

    /**
     * Returns annonces by owner
     *
     * @param UserInterface $owner
     *
     * @return array
     */
    public function getMyAnnonces(UserInterface $owner): array
    {
        return $this->getEntityRepository(Annonce::class)->getAnnoncesByOwner($owner);
    }

    /**
     * @param UserInterface $owner
     * @param mixed         $id
     *
     * @return mixed
     */
    public function getOnlyMyAnnonce(UserInterface $owner, $id)
    {
        return $this->getEntityRepository(Annonce::class)->getOneAnnonceByOwner($owner, $id);
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
     * @param FormInterface $form
     *
     * @return array
     */
    public function renderSearchParameters(FormInterface $form): array
    {
        return [
            'form' => $form->createView(),
            'cities' => $this->getEntityRepository(City::class)->getCitiesByOrderTitle(),
            'rubriques' => $this->getEntityRepository(Rubrique::class)->getAllRubriqueAndCategories()
        ];
    }


    /**
     * @param Request       $request
     * @param AnnonceSearch $search
     *
     * @return PaginationInterface
     */
    public function getAnnoncesByQuerySearch(Request $request, AnnonceSearch $search): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->getEntityRepository(Annonce::class)->findAllAnnonceQuery($search),
            $request->query->getInt('page', 1),
                self::ITEMS_BY_PAGE
        );
    }

    /**
     * @param Request $request
     *
     * @return PaginationInterface
     */
    public function getAnnoncesByProperty(Request $request): PaginationInterface
    {
        $page = $request->query->getInt('page', 1);
        $repository = $this->getEntityRepository(Annonce::class);

        $annonces = $this->paginator->paginate(
            $repository->findAllAnnonces(),
            $page,
            self::ITEMS_BY_PAGE
        );

        if ($category = $request->query->get('category')) {
            return $this->paginator->paginate(
                $repository->getAnnoncesByCategorySlug($category),
                $page,
                self::ITEMS_BY_PAGE
            );
        }

        if ($rubrique = $request->query->get('rubrique')) {
            return $this->paginator->paginate(
                $repository->getAnnoncesByRubriqueSlug($rubrique),
                $page,
                self::ITEMS_BY_PAGE
            );
        }

        if ($city = $request->query->get('city')) {
            return $this->paginator->paginate(
                $repository->getAnnoncesByCitySlug($city),
                $page,
                self::ITEMS_BY_PAGE
            );
        }

        if ($region = $request->query->get('region')) {
            return $this->paginator->paginate(
                $repository->getAnnoncesByRegionSlug($region),
                $page,
                self::ITEMS_BY_PAGE
            );
        }

        return $annonces;
    }

    /**
     * @return JsonResponse
     */
    private function sendErrorResponse(): JsonResponse
    {
        return new JsonResponse(['message' => 'error'], Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
    }

}
