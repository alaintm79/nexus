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
    private const EXCLUDE = ['url_splash', 'url_logo', 'url_sidebar'];
    private const COLUMNS = "o.id, o.token, o.nombre, o.valor, o.isActive";

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Opcion::class);
    }

    public function findAll(): ?array
    {
        $qb = $this->createQueryBuilder('o');

        return $qb->select(self::COLUMNS)
            ->orderBy('o.id', 'asc')
            ->getQuery()
            ->getArrayResult();
    }

    public function findAllButNotExcluded(): ?array
    {
        $qb = $this->createQueryBuilder('o');

        return $qb->select(self::COLUMNS)
            ->where($qb->expr()->notIn('o.token', self::EXCLUDE))
            ->orderBy('o.id', 'asc')
            ->getQuery()
            ->getArrayResult();
    }

    public function findAllWithExcluded(): ?array
    {
        $qb = $this->createQueryBuilder('o');

        return $qb->select(self::COLUMNS)
            ->where($qb->expr()->in('o.token', self::EXCLUDE))
            ->orderBy('o.id', 'asc')
            ->getQuery()
            ->getScalarResult();
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
