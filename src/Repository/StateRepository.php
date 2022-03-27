<?php

namespace App\Repository;

use App\Entity\State;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method State|null find($id, $lockMode = null, $lockVersion = null)
 * @method State|null findOneBy(array $criteria, array $orderBy = null)
 * @method State[]    findAll()
 * @method State[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, State::class);
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function getStateArrayByCategoryType(string $type): array
    {
        return $this
            ->createQueryBuilder('s')
            ->innerJoin('s.categoryState', 'cat')
            ->addSelect('cat')
            ->where('cat.title = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }


}
