<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }
    public function findByIds(array $searchClients=[]): array
    {
        return $this->createQueryBuilder('client')
                ->andWhere('client.id IN (:id)')
                ->setParameter('id', $searchClients)
                ->getQuery()
                ->getResult();
    }
    public function all(): array
    {
        return $this->createQueryBuilder('client')
                ->getQuery()
                ->getResult();
    }
    
    public function findAllDesc(){
        return $this->createQueryBuilder('c')
                ->orderBy('c.id', "DESC")
                ->getQuery()
                ->getResult();
    }
}
