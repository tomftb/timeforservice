<?php

namespace App\Repository;

use App\Entity\ClassificationOfActivities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClassificationOfActivities>
 */
class ClassificationOfActivitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClassificationOfActivities::class);
    }
    public function shortFindAll()
    {
        return $this->createQueryBuilder('c')
                ->select('c.id','c.code','c.name')
                ->orderBy('c.id', 'ASC')
                ->getQuery()
                ->getResult()
            ;
    }

    public function findById(int $id=0): array
    {
        return $this->createQueryBuilder('c')
                ->andWhere('c.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();
    }
}
