<?php

namespace App\Repository;

use App\Entity\Service;
use App\Entity\ClientPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use App\Model\YesOrNoEnum;

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
        $qb->andWhere('service.deleted=:deleted')
                ->setParameter('deleted', YesOrNoEnum::NO);
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
    public function findByClientPointId(int $clientPointId = 0,string $direction = 'DESC'):array
    {
        return $this->createQueryBuilder('service')
                ->andWhere('service.clientPoint=:clientPointId')
                ->setParameter('clientPointId', $clientPointId)
                ->orderBy('service.id',$direction)
                ->getQuery()
                ->getResult();
    }
    public function findByClientId(int $clientId = 0,string $direction = 'DESC'):array
    {
        $qb = self::findByClientIdQueryBuilder($clientId,$direction);
        return $qb->getQuery()->getResult();
    }
    public function findByClientIdQueryBuilder(int $clientId = 0,string $direction = 'DESC'): QueryBuilder
    {
        return $this->createQueryBuilder('service')
                ->leftJoin('service.clientPoint','clientPoint')
                ->leftJoin('clientPoint.client','client')
                ->andWhere('client.id=:clientId')
                ->andWhere('service.deleted=:deleted')
                ->setParameter('deleted', YesOrNoEnum::NO)
                ->setParameter('clientId', $clientId)
                ->orderBy('service.id',$direction);
    }
    public function findByClientIdWithEndedIn(int $clientId = 0,string $direction = 'DESC',string $endedFrom="",string $endedTo=""):array
    {
        $qb = self::findByClientIdQueryBuilder($clientId,$direction);
        return $qb->andWhere('service.endedAt>=:endedFrom')
            ->andWhere('service.endedAt<=:endedTo')
            ->setParameter('endedFrom', $endedFrom)
            ->setParameter('endedTo', $endedTo)
            ->getQuery()
            ->getResult();
    }
    public function findByClientIdWithEndedFrom(int $clientId = 0,string $direction = 'DESC',string $endedFrom="",?string $endedTo=""):array
    {
        $qb = self::findByClientIdQueryBuilder($clientId,$direction);
        return $qb->andWhere('service.endedAt>=:endedFrom')
                ->setParameter('endedFrom', $endedFrom)
                ->getQuery()
                ->getResult();
        }
    public function findByClientIdWithEndedTo(int $clientId = 0,string $direction = 'DESC',?string $endedFrom="",string $endedTo=""):array
    {
        $qb = self::findByClientIdQueryBuilder($clientId,$direction);
        return $qb->andWhere('service.endedAt<=:endedTo')
                ->setParameter('endedTo', $endedTo)
                ->getQuery()
                ->getResult();
    }
}
