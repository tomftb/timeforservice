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
    public function findByWithClientPoint(string $direction = 'DESC'):array
    {
        return $this->createQueryBuilder('service')
                ->leftJoin('service.clientPoint','clientPoint')
                ->orderBy('service.id',$direction)
                ->getQuery()
                ->getResult();
    }
    public function findBySearchWithClientPoint(?string $query, array $searchClientsPoints, int $limit = null): array
    {
        $qb =  $this->findBySearchWithClientPointQueryBuilder($query, $searchClientsPoints);
        if ($limit) {
            $qb->setMaxResults($limit);
        }
        return $qb
            ->getQuery()
            ->getResult();
    }
    public function findBySearchWithClientPointQueryBuilder(?string $query, array $searchClientsPoints, ?string $sort = null, string $direction = 'DESC'): QueryBuilder
    {
        $qb = $this->createQueryBuilder('service');
        if ($query) {
            $qb->andWhere('service.description LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }
        if ($searchClientsPoints) {
            $qb->andWhere('service.clientPoint IN (:id)')
                ->setParameter('id', $searchClientsPoints);
        }
        if ($sort) {
            $qb->orderBy('service.'.$sort, $direction);
        }
        return $qb;
    }
}
