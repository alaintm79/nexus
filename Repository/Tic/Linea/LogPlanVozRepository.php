<?php

namespace App\Repository\Tic\Linea;

use App\Entity\Tic\Linea\LogPlanVoz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LogPlanVoz|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogPlanVoz|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogPlanVoz[]    findAll()
 * @method LogPlanVoz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogPlanVozRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogPlanVoz::class);
    }

    /**
     * @return LogPlanVoz[] Returns an array of LogPlanVoz objects
     */
    public function findByLineaId(int $id)
    {
        return $this->createQueryBuilder('l')
            ->select('l.id, l.fechaCreado')
            ->addSelect('p.plan, p.cuotaMensual')
            ->leftJoin('l.plan', 'p')
            ->andWhere('l.linea = :id')
            ->setParameter('id', $id)
            ->orderBy('l.id', 'DESC')
            ->getQuery()
            ->getScalarResult()
        ;
    }
}
