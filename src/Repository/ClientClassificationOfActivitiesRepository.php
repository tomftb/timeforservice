<?php

namespace App\Repository;

use App\Repository\ClassificationOfActivitiesRepository;
use App\Entity\ClientClassificationOfActivities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientClassificationOfActivities>
 */
class ClientClassificationOfActivitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientClassificationOfActivities::class);
    }
    public function findByIds(int $clientId=0, int $classificationId=0 ): array
    {
        return $this->createQueryBuilder('c')
                ->andWhere('c.client_id = :clientId')
                ->andWhere('`c`.`classification_id` = :classificationId')
                ->setParameter('clientId', $clientId)
                ->setParameter('classificationId', $classificationId)
                ->getQuery()
                ->getResult();
    }
    public function tightFindByClientId(int $clientId=0): array
    {
        return $this->createQueryBuilder('c')
                ->select('c.id','c.price','identity(c.classification) classificationId')
                ->andWhere('c.client = :clientId')
                ->setParameter('clientId', $clientId)
                ->getQuery()
                ->getResult();
    }
    public function findByClientId(int $clientId=0): array
    {
        return $this->createQueryBuilder('c')
                ->andWhere('c.client = :clientId')
                ->setParameter('clientId', $clientId)
                ->getQuery()
                ->getResult();
    }
}
