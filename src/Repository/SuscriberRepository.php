<?php

namespace App\Repository;

use App\Entity\Suscriber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Suscriber|null find($id, $lockMode = null, $lockVersion = null)
 * @method Suscriber|null findOneBy(array $criteria, array $orderBy = null)
 * @method Suscriber[]    findAll()
 * @method Suscriber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuscriberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Suscriber::class);
    }

    // /**
    //  * @return Suscriber[] Returns an array of Suscriber objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Suscriber
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
