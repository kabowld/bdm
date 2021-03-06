<?php

namespace App\Repository;

use App\Entity\Annonce;
use App\Entity\AnnonceSearch;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    /**
     * @param UserInterface $user
     *
     * @return array
     */
    public function getAnnoncesByOwner(UserInterface $user): array
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.owner = :user')
            ->orderBy('a.createdAt', 'DESC')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }


    /**
     * @param UserInterface $user
     * @param mixed         $id
     *
     * @throws NonUniqueResultException
     *
     * @return Annonce|null
     */
    public function getOneAnnonceByOwner(UserInterface $user, $id): ?Annonce
    {
        return $this->createQueryBuilder('a')
            ->where('a.owner = :user')
            ->andWhere('a.id = :id')
            ->setParameters(['user' => $user, 'id' => $id])
            ->getQuery()
            ->getOneOrNullResult();
    }


    /**
     * Get annonces By Category
     *
     * @param string $slug
     *
     * @return Annonce[]
     */
    public function getAnnoncesByCategorySlug(string $slug): array
    {
        $qb = $this->createQueryBuilder('a');
        return $qb
            ->innerJoin('a.category', 'cat')
            ->leftJoin('a.pack', 'pack')
            ->addSelect('cat')
            ->addSelect('pack')
            ->where($qb->expr()->eq('cat.slug', ':slug'))
            ->addOrderBy('pack.id', 'DESC')
            ->addOrderBy('a.createdAt', 'DESC')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get Annonces with same category
     *
     * @param Category $catEntity
     *
     * @return array
     */
    public function getAnnoncesBySameCategory(Category $catEntity): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->innerJoin('a.category', 'cat')
            ->addSelect('cat')
            ->where('cat.id = :cate')
            ->orderBy('a.createdAt', 'DESC')
            ->setParameter('cate', $catEntity->getId())
            ->setMaxResults(4);

        return $qb->getQuery()->getResult();
    }

    /**
     * Get Annonce By Rubrique
     *
     * @param string $slug
     *
     * @return array
     */
    public function getAnnoncesByRubriqueSlug(string $slug): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->innerJoin('a.category', 'cat')
            ->innerJoin('cat.rubrique', 'rub')
            ->addSelect('cat')
            ->addSelect('rub')
            ->where($qb->expr()->eq('rub.slug', ':slug'))
            ->leftJoin('a.pack', 'pack')
            ->addSelect('pack')
            ->addOrderBy('pack.id', 'DESC')
            ->addOrderBy('a.createdAt', 'DESC')
            ->setParameter('slug', $slug);

        return $qb->getQuery()->getResult();
    }

    /**
     * Get Annonce By city
     *
     * @param string $slug
     *
     * @return array
     */
    public function getAnnoncesByCitySlug(string $slug): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->innerJoin('a.city', 'city')
            ->addSelect('city')
            ->where($qb->expr()->eq('city.slug', ':slug'))
            ->leftJoin('a.pack', 'pack')
            ->addSelect('pack')
            ->addOrderBy('pack.id', 'DESC')
            ->addOrderBy('a.createdAt', 'DESC')
            ->setParameter('slug', $slug);

        return $qb->getQuery()->getResult();
    }

    /**
     * Get Annonce By Region
     *
     * @param string $slug
     *
     * @return int|mixed|string
     */
    public function getAnnoncesByRegionSlug(string $slug)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->innerJoin('a.city', 'city')
            ->innerJoin('city.region', 'reg')
            ->addSelect('city')
            ->addSelect('reg')
            ->where($qb->expr()->eq('reg.slug', ':slug'))
            ->leftJoin('a.pack', 'pack')
            ->addSelect('pack')
            ->addOrderBy('pack.id', 'DESC')
            ->addOrderBy('a.createdAt', 'DESC')
            ->setParameter('slug', $slug);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param AnnonceSearch $search
     *
     * @return Query
     */
    public function findAllAnnonceQuery(AnnonceSearch $search): Query
    {
        $query = $this
            ->createQueryBuilder('a')
            ->leftJoin('a.pack', 'pack')
            ->addSelect('pack')
            ->addOrderBy('pack.id', 'DESC')
            ->addOrderBy('a.createdAt', 'DESC');

        if (in_array($search->getType(), AnnonceSearch::TYPE)) {
            $query = $query
                ->andWhere('a.type = :type')
                ->setParameter('type', $search->getType());
        }

        if ($search->getCategory()) {
            $query = $query
                ->innerJoin('a.category', 'cat')
                ->addSelect('cat')
                ->andWhere('cat.id = :category')
                ->setParameter('category', $search->getCategory());
        }

        if ($search->getCity()) {
            $query = $query
                ->innerJoin('a.city', 'city')
                ->addSelect('city')
                ->andWhere('city.id = :city')
                ->setParameter('city', $search->getCity());
        }

        if (!empty($search->getSearch())) {
            $query = $query
                ->andWhere($query->expr()->orX(
                    $query->expr()->like('a.title', $query->expr()->literal('%'.$search->getSearch().'%')),
                    $query->expr()->like('a.description', $query->expr()->literal('%'.$search->getSearch().'%'))
                ));
        }

        return $query->getQuery();
    }

    /**
     * Get last five annonces
     *
     * @param int $limit
     *
     * @return array
     */
    public function getLastFiveAnnonces(int $limit): array
    {
        return $this
            ->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Annonce[]
     */
    public function findAllAnnonces(): array
    {
        return $this
            ->createQueryBuilder('a')
            ->leftJoin('a.pack', 'pack')
            ->addSelect('pack')
            ->addOrderBy('pack.id', 'DESC')
            ->addOrderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $currentUser
     *
     * @return Query
     */
    public function getFavoris(User $currentUser): Query
    {
        return $this
            ->createQueryBuilder('a')
            ->innerJoin('a.usersFavoris', 'user')
            ->addSelect('user')
            ->where('user = :currentUser')
            ->orderBy('a.createdAt', 'DESC')
            ->setParameter('currentUser', $currentUser)
            ->getQuery();
    }

}
