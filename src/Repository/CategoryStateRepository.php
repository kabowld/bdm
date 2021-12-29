<?php

namespace App\Repository;

use App\Entity\CategoryState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoryState|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryState|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryState[]    findAll()
 * @method CategoryState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryState::class);
    }

    // /**
    //  * @return CategoryState[] Returns an array of CategoryState objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryState
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
