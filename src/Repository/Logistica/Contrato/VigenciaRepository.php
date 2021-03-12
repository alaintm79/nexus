<?php

namespace App\Repository\Logistica\Contrato;

use App\Entity\Logistica\Contrato\Vigencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vigencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vigencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vigencia[]    findAll()
 * @method Vigencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VigenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vigencia::class);
    }
}
