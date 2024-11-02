<?php

namespace App\Repository;

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
    //    /**
    //     * @return ClientClassificationOfActivities[] Returns an array of ClientClassificationOfActivities objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ClientClassificationOfActivities
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
