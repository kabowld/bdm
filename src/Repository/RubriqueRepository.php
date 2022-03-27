<?php

namespace App\Repository;

use App\Entity\Rubrique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rubrique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rubrique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rubrique[]    findAll()
 * @method Rubrique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RubriqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rubrique::class);
    }

    /**
     * @return array
     */
    public function getAllRubriqueByOrderAsc(): array
    {
        return $this
            ->createQueryBuilder('r')
            ->orderBy('r.title')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function getAllRubriqueAndCategories(): array
    {
        return $this
            ->createQueryBuilder('r')
            ->innerJoin('r.categories', 'cat')
            ->addSelect('cat')
            ->getQuery()
            ->getResult();
    }
}
