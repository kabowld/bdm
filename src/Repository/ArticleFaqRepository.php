<?php

namespace App\Repository;

use App\Entity\ArticleFaq;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArticleFaq|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleFaq|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleFaq[]    findAll()
 * @method ArticleFaq[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleFaqRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleFaq::class);
    }

    // /**
    //  * @return ArticleFaq[] Returns an array of ArticleFaq objects
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
    public function findOneBySomeField($value): ?ArticleFaq
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
