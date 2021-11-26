<?php

namespace App\Repository\Blog;

use App\Entity\Blog\PublicacionCounter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PublicacionCounter|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicacionCounter|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicacionCounter[]    findAll()
 * @method PublicacionCounter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicacionCounterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicacionCounter::class);
    }

    public function findPublicacionCounterById(int $id){
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.publicacion = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
