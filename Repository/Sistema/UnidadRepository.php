<?php

namespace App\Repository\Sistema;

use App\Entity\Sistema\Unidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Unidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Unidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Unidad[]    findAll()
 * @method Unidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unidad::class);
    }

    public function findAllUnidades()
    {
        return $this->createQueryBuilder('u')
                ->getQuery()
                ->getResult()
        ;
    }

    public function findByNombre ($unidad)
    {
        $qb = $this->createQueryBuilder('u');

        if($unidad !== 'ALL'){
            $qb->where('u.nombre = :unidad')
                ->setParameter('unidad', $unidad);
        }

        $qb->getQuery()
            ->useQueryCache(true);

        return $qb;
    }
}
