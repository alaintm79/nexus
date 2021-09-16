<?php

namespace App\Repository\Sistema;

use App\Entity\Sistema\AccessLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccessLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessLog[]    findAll()
 * @method AccessLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessLog::class);
    }
}
