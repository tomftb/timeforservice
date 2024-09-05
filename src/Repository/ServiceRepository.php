<?php

namespace App\Repository;

use App\Entity\Service;
use App\Entity\ClientPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }
    /**
     * @return Voyage[]
     */
    public function findBySearch(?string $query, array $searchServices, int $limit = null): array
    {
        $qb =  $this->findBySearchQueryBuilder($query, $searchServices);

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function findBySearchQueryBuilder(?string $query, array $searchServices, ?string $sort = null, string $direction = 'DESC'): QueryBuilder
    {
        $qb = $this->createQueryBuilder('s');

        if ($query) {
            $qb->andWhere('s.description LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        if ($searchServices) {
            $qb->andWhere('s.id IN (:id)')
                ->setParameter('id', $searchServices);
        }

        if ($sort) {
            $qb->orderBy('s.' . $sort, $direction);
        }

        return $qb;
    }
    public function findByWithClientPoint(string $direction = 'DESC'):array
    {
        return $this->createQueryBuilder('service')
                ->leftJoin('service.clientPoint','clientPoint')
                ->orderBy('service.id',$direction)
                ->getQuery()
                ->getResult();
    }
    //    /**
    //     * @return Service[] Returns an array of Service objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Service
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
