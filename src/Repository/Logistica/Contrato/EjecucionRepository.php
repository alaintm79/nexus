<?php

namespace App\Repository\Logistica\Contrato;

use App\Entity\Logistica\Contrato\Ejecucion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ejecucion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ejecucion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ejecucion[]    findAll()
 * @method Ejecucion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EjecucionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ejecucion::class);
    }

    public function findEjecucionByContratoId(int $id): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.saldoCup, e.saldoCuc')
            ->addSelect('s.noDocumentoPrimario, s.noDocumentoSecundario')
            ->addSelect('s.importeCup, s.importeCuc')
            ->addSelect('d.tipo')
            ->addSelect('c.id')
            ->leftJoin('e.solicitud', 's')
            ->leftJoin('s.tipoDocumento', 'd')
            ->leftJoin('s.contrato', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getScalarResult();
    }

    public function findEjecucionByRango(string $inicio, string $fin): array
    {
        $qb = $this->createQueryBuilder('e');

        return $qb->select('e.saldoCup, e.saldoCuc,e.fechaModificacion')
            ->addSelect('s.noDocumentoPrimario, s.noDocumentoSecundario')
            ->addSelect('s.importeCup, s.importeCuc')
            ->addSelect('d.tipo')
            ->addSelect('c.id, c.numero')
            ->addSelect('u.nombre AS unidad')
            ->addSelect('pc.nombre AS proveedorCliente')
            ->leftJoin('e.solicitud', 's')
            ->leftJoin('s.tipoDocumento', 'd')
            ->leftJoin('s.contrato', 'c')
            ->leftJoin('c.proveedorCliente', 'pc')
            ->leftJoin('c.procedencia', 'u')
            ->where($qb->expr()->between('e.fechaModificacion', ':inicio', ':fin'))
            ->setParameter('inicio', $inicio)
            ->setParameter('fin', $fin)
            ->getQuery()
            ->getScalarResult();
    }
}
