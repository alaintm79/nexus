<?php

namespace App\Repository\Logistica\SolicitudPago;

use App\Entity\Logistica\SolicitudPago\Acapite;
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

    // /**
    //  * @return Acapite[] Returns an array of Acapite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Acapite
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
