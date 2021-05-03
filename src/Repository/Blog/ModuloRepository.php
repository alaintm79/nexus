<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Modulo;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Modulo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Modulo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Modulo[]    findAll()
 * @method Modulo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuloRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Modulo::class);
    }

    public function findAll(): ?array
    {
        return $this->createQueryBuilder('m')
            ->select("m.id, m.nombre AS modulo, m.isActive")
            ->orderBy('m.id', 'asc')
            ->getQuery()
            ->getArrayResult();
    }
}
