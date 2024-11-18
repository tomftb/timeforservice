<?php

namespace App\Repository;

use App\Entity\ServiceAttachment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServiceAttachment>
 */
class ServiceAttachmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceAttachment::class);
    }
    /**
     * @return ServiceAttachment[] Returns an array of ServiceAttachment objects
     */
    public function findByService(int $serviceId=0): array
    {
        return $this->createQueryBuilder('service_attachment')
                ->andWhere('service_attachment.service = :service')
                ->setParameter('service', $serviceId)
                ->getQuery()
                ->getResult();
    }
}
