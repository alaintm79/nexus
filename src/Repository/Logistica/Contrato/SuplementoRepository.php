<?php

namespace App\Repository\Logistica\Contrato;

use App\Entity\Logistica\Contrato\Suplemento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Suplemento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Suplemento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Suplemento[]    findAll()
 * @method Suplemento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuplementoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Suplemento::class);
    }

    public function findByContratoId(int $id): array
    {
        return $this->createQueryBuilder('s')
            ->select('s.id, s.objeto, s.observacion, s.fechaFirma, s.fechaModificacion')
            ->addSelect('e.estado, v.vigencia')
            ->leftJoin('s.estado', 'e')
            ->leftJoin('s.vigencia', 'v')
            ->leftJoin('s.contrato', 'c')
            ->where('c.id = :id')
            ->orderBy('s.id', 'DESC')
            ->setParameter('id', $id)
            ->getQuery()
            ->getScalarResult();
    }

    public function findById(int $id): ?Suplemento
    {
        return $this->createQueryBuilder('s')
            ->select('s, e, v, c')
            ->leftJoin('s.contrato', 'c')
            ->leftJoin('s.estado', 'e')
            ->leftJoin('s.vigencia', 'v')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}
