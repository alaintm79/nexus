<?php

namespace App\Repository\Tic\Linea;

use App\Entity\Tic\Linea\PlanExtra;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlanExtra|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanExtra|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanExtra[]    findAll()
 * @method PlanExtra[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanExtraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanExtra::class);
    }

    /**
     * @return PlanExtra[] Returns an array of PlanExtra objects
     */

    public function findByIdLinea(int $id): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.montoMinutos, p.fechaCreado, p.observacion')
            ->addSelect("CONCAT(u.nombre, ' ', u.apellidos) AS usuario")
            ->leftJoin('p.linea', 'l')
            ->leftJoin('l.usuario', 'u')
            ->andWhere('p.linea = :id')
            ->setParameter('id', $id)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getScalarResult()
        ;
    }

    public function findTotalByIdLinea(int $id): ?int
    {
        return $this->createQueryBuilder('p')
            ->select('SUM(p.montoMinutos)')
            ->andWhere('p.linea = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

}
