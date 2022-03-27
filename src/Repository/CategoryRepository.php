<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }


    /**
     * @param int $rubriqueId
     *
     * @throws Exception
     *
     * @return array
     */
    public function getCategoriesByRubrique(int $rubriqueId): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT cat.title, cat.id FROM category AS cat
            INNER JOIN rubrique AS rub
            ON cat.rubrique_id = rub.id
            WHERE rub.id = :id
            ORDER BY cat.title ASC
            ';
        $stmt = $conn->prepare($sql);
        $exec = $stmt->executeQuery(['id' => $rubriqueId]);

        return $exec->fetchAllKeyValue();
    }

}
