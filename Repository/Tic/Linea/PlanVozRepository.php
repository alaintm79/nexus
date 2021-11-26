<?php

namespace App\Repository\Tic\Linea;

use App\Entity\Tic\Linea\PlanVoz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlanVoz||null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanVoz||null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanVoz[]    findAll()
 * @method PlanVoz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanVozRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanVoz::class);
    }
}
