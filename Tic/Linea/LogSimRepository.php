<?php

namespace App\Repository\Tic\Linea;

use App\Entity\Tic\Linea\LogSim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LogSim|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogSim|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogSim[]    findAll()
 * @method LogSim[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogSimRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogSim::class);
    }

    /**
     * @return LogSim[] Returns an array of LogSim
     */
    public function findByLineaId(int $id)
    {
        return $this->createQueryBuilder('l')
            ->select('l.id, l.pin, l.puk, l.fechaCreado')
            ->andWhere('l.linea = :id')
            ->setParameter('id', $id)
            ->orderBy('l.id', 'DESC')
            ->getQuery()
            ->getScalarResult()
        ;
    }
}
