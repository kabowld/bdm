<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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
        return $this->createQueryBuilder('a')
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
}
