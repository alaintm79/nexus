<?php

namespace App\Repository\Tic\Nomenclador;

use App\Entity\Tic\Nomenclador\Estado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Estado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estado[]    findAll()
 * @method Estado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Estado::class);
    }
}
