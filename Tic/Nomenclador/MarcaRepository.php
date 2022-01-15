<?php

namespace App\Repository\Tic\Nomenclador;

use App\Entity\Tic\Nomenclador\Marca;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Marca|null find($id, $lockMode = null, $lockVersion = null)
 * @method Marca|null findOneBy(array $criteria, array $orderBy = null)
 * @method Marca[]    findAll()
 * @method Marca[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarcaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Marca::class);
    }

    public function findAllMarcas(): ?array
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m.id, m.marca')
        ;

        return  $qb->getQuery()
                    ->getScalarResult();
    }
}
