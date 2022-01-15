<?php

namespace App\Repository\Tic\Linea;

use App\Entity\Tic\Linea\PlanAdicional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlanAdicional|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanAdicional|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanAdicional[]    findAll()
 * @method PlanAdicional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanAdicionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanAdicional::class);
    }
}
