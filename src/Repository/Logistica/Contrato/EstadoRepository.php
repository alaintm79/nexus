<?php

namespace App\Repository\Logistica\Contrato;

use App\Entity\Logistica\Contrato\Estado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Estado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estado[]    findAll()
 * @method Estado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Estado::class);
    }

    public function findByEstado(array $estado)
    {
        $qb = $this->createQueryBuilder('e');

        return $qb->where($qb->expr()->in('e.estado', $estado));
    }

}
