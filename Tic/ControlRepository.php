<?php

namespace App\Repository\Tic;

use App\Entity\Tic\Control;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Control|null find($id, $lockMode = null, $lockVersion = null)
 * @method Control|null findOneBy(array $criteria, array $orderBy = null)
 * @method Control[]    findAll()
 * @method Control[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ControlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Control::class);
    }

    public function findControlByUnidadAndMedio(string $unidad, string $medio): array
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c.id, c.inventarios, c.fechaCreado')
            ->addSelect("CONCAT(u.nombre, ' ', u.apellidos) AS controla")
            ->addSelect('un.nombre AS unidad')
            ->join('c.usuario', 'u')
            ->join('c.medio', 'm')
            ->join('u.unidad', 'un')
            ->where('m.tipo = :medio')
            ->orderBy('c.id', 'DESC')
            ->setParameter('medio', $medio)
        ;

        if($unidad !== 'ALL'){
            $qb->andWhere('un.nombre = :unidad')
                ->setParameter('unidad', $unidad);
        }

        return $qb->getQuery()
                    ->getScalarResult();
    }

    public function findControlByIdAndUnidad(int $id, string $unidad): ?Control
    {

        $qb = $this->createQueryBuilder('c')
            ->select('c, u, un, s')
            ->join('c.usuario', 'u')
            ->join('u.unidad', 'un')
            ->leftJoin('u.servicio', 's')
            ->where('c.id = :id')
            ->setParameter('id', $id);

        if($unidad !== 'ALL'){
            $qb->andWhere('un.nombre = :unidad')
                ->setParameter('unidad', $unidad);
        }

        return $qb->getQuery()
            ->getSingleResult();
    }

    public function findOneControlById(int $id): ?Control
    {
        return $this->createQueryBuilder('c')
            ->select('c, u, un')
            ->leftJoin('c.usuario', 'u')
            ->leftJoin('u.unidad', 'un')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
