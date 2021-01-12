<?php

namespace App\Repository\Sistema;

use App\Entity\Sistema\Plaza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Plaza|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plaza|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plaza[]    findAll()
 * @method Plaza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlazaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plaza::class);
    }

    public function findPlazas()
    {
        return $this->createQueryBuilder('p')
            ->select("p.id, p.nombre AS plaza")
            ->getQuery()
            ->getArrayResult();
    }
}
