<?php

namespace App\Repository;

use App\Entity\DetailsPack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DetailsPack|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailsPack|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailsPack[]    findAll()
 * @method DetailsPack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailsPackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailsPack::class);
    }

    // /**
    //  * @return DetailsPack[] Returns an array of DetailsPack objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DetailsPack
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
