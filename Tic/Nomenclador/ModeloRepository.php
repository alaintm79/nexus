<?php

namespace App\Repository\Tic\Nomenclador;

use App\Entity\Tic\Nomenclador\Modelo;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Modelo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Modelo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Modelo[]    findAll()
 * @method Modelo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeloRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Modelo::class);
    }

    public function findAllModelos(): ?array
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m.id, m.modelo, m.imagen')
            ->addSelect('ma.marca')
            ->addSelect('t.tipo')
            ->leftJoin('m.marca', 'ma')
            ->leftJoin('m.tipo', 't')
        ;

        return  $qb->getQuery()
                    ->getScalarResult();
    }
}
