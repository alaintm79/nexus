<?php

namespace App\Repository\Logistica\Pago;

use App\Entity\Logistica\Pago\Documento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Documento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Documento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Documento[]    findAll()
 * @method Documento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Documento::class);
    }
}
