<?php

namespace App\Repository\Logistica\Pago;

use App\Entity\Logistica\Pago\Tipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tipo[]    findAll()
 * @method Tipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tipo::class);
    }
}
