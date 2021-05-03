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
    private const EXCLUDE = ['URL_SPLASH', 'URL_LOGO'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Opcion::class);
    }

    public function findAll(): ?array
    {
        $qb = $this->createQueryBuilder('o');

        return $qb->select("o.id, o.token, o.nombre, o.valor, o.isActive")
            ->orderBy('o.id', 'asc')
            ->getQuery()
            ->getArrayResult();
    }

    public function findAllButNotExcluded(): ?array
    {
        $qb = $this->createQueryBuilder('o');

        return $qb->select("o.id, o.token, o.nombre, o.valor, o.isActive")
            ->where($qb->expr()->notIn('o.token', self::EXCLUDE))
            ->orderBy('o.id', 'asc')
            ->getQuery()
            ->getArrayResult();
    }

    public function findAllWithExcluded(): ?array
    {
        $qb = $this->createQueryBuilder('o');

        $qb->select("o.id, o.token, o.nombre, o.valor, o.isActive")
            ->where($qb->expr()->in('o.token', self::EXCLUDE))
            ->orderBy('o.id', 'asc');

        $opciones = $qb->getQuery()
                        ->getScalarResult();

        return $opciones;
    }

    public function findByToken($token): ?Opcion
    {
        return $this->createQueryBuilder('o')
            ->where('o.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
