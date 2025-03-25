<?php

namespace App\Repository;

use App\Entity\ClientPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ClientPoint>
 */
class ClientPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientPoint::class);
    }
    public function findAllQueryBuilder(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('clientPoint');
        $qb->orderBy('clientPoint.id', "ASC");
        return $qb;
    }
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('cp')
                ->leftJoin('cp.client', "c")
                ->andWhere('c.active = :activeClient')
                ->setParameter('activeClient', "YES")
                ->andWhere('c.deleted = :deletedClient')
                ->setParameter('deletedClient', "NO")
                ->andWhere('cp.active = :active')
                ->setParameter('active', "YES")
                ->andWhere('cp.deleted = :deleted')
                ->setParameter('deleted', "NO")
                ->orderBy('cp.name', "ASC")
                ->getQuery()
                ->getResult();
    }
    public function getSelected(int $id=0): array
    {
        return $this->createQueryBuilder('cp')
                ->andWhere('cp.id = :id')
                ->setParameter('id', $id)
                ->orderBy('cp.name', "ASC")
                ->getQuery()
                ->getResult();
    }
    public function findAllActiveWithSelected(int $id=0): QueryBuilder
    {
       return $this->createQueryBuilder('cp')
                ->andWhere('cp.id = :id')
                ->setParameter('id', $id)
                ->orderBy('cp.name', "ASC")
                ->join('cp.id', "client_point")
                ->andWhere('cp2.id != cp.id')
                ->andWhere('cp2.active = :active')
                ->setParameter('active', "YES")
                ->andWhere('cp2.deleted = :deleted')
                ->setParameter('deleted', "NO")
                ->orderBy('cp2.name', "ASC");
    }
}
