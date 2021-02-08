<?php

namespace App\Repository\Logistica\SolicitudPago;

use App\Entity\Logistica\SolicitudPago\SolicitudPago;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SolicitudPago|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudPago|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudPago[]    findAll()
 * @method SolicitudPago[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudPagoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitudPago::class);
    }

    // /**
    //  * @return SolicitudPago[] Returns an array of SolicitudPago objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SolicitudPago
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
