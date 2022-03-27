<?php

namespace App\Repository;

use App\Entity\Pack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pack|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pack|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pack[]    findAll()
 * @method Pack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pack::class);
    }


    /**
     * @param $id
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     *
     * @return array
     */
    public function getPackAjax($id): array
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.id = :identifier')
            ->setParameter('identifier', $id)
            ->getQuery()
            ->getSingleResult();
    }

}
