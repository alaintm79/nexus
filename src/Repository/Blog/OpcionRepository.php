<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Opcion;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Opcion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Opcion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Opcion[]    findAll()
 * @method Opcion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpcionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Opcion::class);
    }

    public function findOpciones(): ?array
    {
        return $this->createQueryBuilder('o')
            ->select("o.id, o.nombre, o.valor, o.isActive")
            ->orderBy('o.id', 'asc')
            ->getQuery()
            ->getArrayResult();
    }

    public function findOpcionById(int $id): ?Opcion
    {
        return $this->createQueryBuilder('o')
            ->where('o.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}
