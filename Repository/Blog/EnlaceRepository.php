<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Enlace;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Enlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enlace[]    findAll()
 * @method Enlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enlace::class);
    }

    public function findAll(): ?array
    {
        return $this->createQueryBuilder('e')
            ->select('e.id, e.titulo, e.url, e.isMenu, e.isActive, UPPER(t.tipo) AS tipo')
            ->leftJoin('e.tipo', 't')
            ->orderBy('e.id', 'asc')
            ->getQuery()
            ->getArrayResult();
    }

    public function findByIsActive(): ?array
    {
        return $this->createQueryBuilder('e')
            ->select('e.id, e.titulo, e.url, e.isMenu, e.isActive, t.tipo')
            ->leftJoin('e.tipo', 't')
            ->where('e.isActive = true')
            ->orderBy('e.id', 'asc')
            ->orderBy('t.tipo')
            ->addOrderBy('e.id')
            ->getQuery()
            ->getScalarResult();
    }

    public function findByIsMenu(): ?array
    {
        return $this->createQueryBuilder('e')
            ->select('e.id, e.titulo, e.url, e.isMenu, e.isActive, UPPER(t.tipo) AS tipo')
            ->leftJoin('e.tipo', 't')
            ->where('e.isActive = true')
            ->andWhere('e.isMenu = true')
            ->orderBy('e.tipo', 'asc')
            ->getQuery()
            ->getScalarResult();
    }
}
