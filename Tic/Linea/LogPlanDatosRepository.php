<?php

namespace App\Repository\Tic\Linea;

use App\Entity\Tic\Linea\LogPlanDatos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LogPlanDatos|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogPlanDatos|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogPlanDatos[]    findAll()
 * @method LogPlanDatos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogPlanDatosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogPlanDatos::class);
    }

    /**
     * @return LogPlanDatos[] Returns an array of LogPlanDatos
     */
    public function findByLineaId(int $id)
    {
        return $this->createQueryBuilder('l')
            ->select('l.id, l.fechaCreado')
            ->addSelect('p.plan, p.rentaMensual')
            ->leftJoin('l.plan', 'p')
            ->andWhere('l.linea = :id')
            ->setParameter('id', $id)
            ->orderBy('l.id', 'DESC')
            ->getQuery()
            ->getScalarResult()
        ;
    }
}
