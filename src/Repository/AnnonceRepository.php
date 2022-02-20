<?php

namespace App\Repository;

use App\Entity\Annonce;
use App\Entity\AnnonceSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    // /**
    //  * @return Annonce[] Returns an array of Annonce objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Annonce
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param UserInterface $user
     *
     * @return array
     */
    public function getAnnoncesByOwner(UserInterface $user): array
    {
        return
            $this
                ->createQueryBuilder('a')
                ->where('a.owner = :user')
                ->orderBy('a.createdAt', 'DESC')
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult()
            ;
    }


    public function getOneAnnonceByOwner(UserInterface $user, $id)
    {
        return $this->createQueryBuilder('a')
            ->where('a.owner = :user')
            ->andWhere('a.id = :id')
            ->setParameters(['user' => $user, 'id' => $id])
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }


    public function getAnnoncesByCategorySlug(string $slug): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->innerJoin('a.category', 'cat')
            ->addSelect('cat')
            ->where($qb->expr()->eq('cat.slug', ':slug'))
            ->orderBy('a.createdAt', 'DESC')
            ->setParameter('slug', $slug)
        ;

        return $qb->getQuery()->getResult();
    }


    public function getAnnoncesByRubriqueSlug(string $slug): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->innerJoin('a.category', 'cat')
            ->innerJoin('cat.rubrique', 'rub')
            ->addSelect('cat')
            ->addSelect('rub')
            ->where($qb->expr()->eq('rub.slug', ':slug'))
            ->orderBy('a.createdAt', 'DESC')
            ->setParameter('slug', $slug)
            ;

        return $qb->getQuery()->getResult();
    }

    public function getAnnoncesByCitySlug(string $slug): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->innerJoin('a.city', 'city')
            ->addSelect('city')
            ->where($qb->expr()->eq('city.slug', ':slug'))
            ->orderBy('a.createdAt', 'DESC')
            ->setParameter('slug', $slug)
        ;

        return $qb->getQuery()->getResult();
    }

    public function getAnnoncesByRegionSlug(string $slug)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->innerJoin('a.city', 'city')
            ->innerJoin('city.region', 'reg')
            ->addSelect('city')
            ->addSelect('reg')
            ->where($qb->expr()->eq('reg.slug', ':slug'))
            ->orderBy('a.createdAt', 'DESC')
            ->setParameter('slug', $slug)
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * @param AnnonceSearch $search
     *
     * @return Query
     */
    public function findAllAnnonceQuery(AnnonceSearch $search): Query
    {
        $query = $this->createQueryBuilder('a');

        if (in_array($search->getType(), AnnonceSearch::TYPE))  {
            $query = $query
                ->andWhere('a.type = :type')
                ->setParameter('type', $search->getType())
            ;
        }

        if ($search->getCategory())  {
            $query = $query
                ->innerJoin('a.category', 'cat')
                ->addSelect('cat')
                ->andWhere('cat.id = :category')
                ->setParameter('category', $search->getCategory())
            ;
        }

        if ($search->getCity())  {
            $query = $query
                ->innerJoin('a.city', 'city')
                ->addSelect('city')
                ->andWhere('city.id = :city')
                ->setParameter('city', $search->getCity())
            ;
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

}
