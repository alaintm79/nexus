<?php

namespace App\Repository\Logistica\Pago;

use App\Entity\Logistica\Pago\Acapite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Acapite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acapite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acapite[]    findAll()
 * @method Acapite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcapiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Acapite::class);
    }
}
