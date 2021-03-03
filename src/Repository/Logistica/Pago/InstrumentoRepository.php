<?php

namespace App\Repository\Logistica\Pago;

use App\Entity\Logistica\Pago\Instrumento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Instrumento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Instrumento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Instrumento[]    findAll()
 * @method Instrumento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstrumentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instrumento::class);
    }
}
