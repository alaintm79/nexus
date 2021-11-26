<?php

namespace App\Repository\Tic\Linea;

use App\Entity\Tic\Linea\PlanDatos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlanDatos|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanDatos|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanDatos[]    findAll()
 * @method PlanDatos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanDatosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanDatos::class);
    }
}
