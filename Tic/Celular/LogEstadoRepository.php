<?php

namespace App\Repository\Tic\Celular;

use App\Entity\Tic\Celular\LogEstado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LogEstado|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogEstado|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogEstado[]    findAll()
 * @method LogEstado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogEstadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogEstado::class);
    }

    /**
     * @return LogEstado[] Returns an array of LogEstados
     */
    public function findByCelularId(int $id)
    {
        return $this->createQueryBuilder('l')
            ->select('l.id, l.fechaCreado')
            ->addSelect('e.estado')
            ->leftJoin('l.celular', 'c')
            ->leftJoin('l.estado', 'e')
            ->andWhere('l.celular = :id')
            ->setParameter('id', $id)
            ->orderBy('l.id', 'DESC')
            ->getQuery()
            ->getScalarResult()
        ;
    }
}
