<?php

namespace App\Repository\Tic\Celular;

use App\Entity\Tic\Celular\LogUsuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LogUsuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogUsuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogUsuario[]    findAll()
 * @method LogUsuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogUsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogUsuario::class);
    }

    /**
     * @return LogUsuario[] Returns an array of LogUsuario
     */
    public function findByCelularId(int $id)
    {
        return $this->createQueryBuilder('l')
            ->select('l.id, l.fechaCreado')
            ->addSelect('u.nombre, u.apellidos')
            ->addSelect('un.nombre AS unidad')
            ->addSelect('p.nombre AS plaza')
            ->leftJoin('l.celular', 'c')
            ->leftJoin('l.usuario', 'u')
            ->leftJoin('u.plaza', 'p')
            ->leftJoin('u.unidad', 'un')
            ->andWhere('l.celular = :id')
            ->setParameter('id', $id)
            ->orderBy('l.id', 'DESC')
            ->getQuery()
            ->getScalarResult()
        ;
    }
}
