<?php

namespace App\Repository\Logistica\Pago;

use App\Entity\Logistica\Pago\Solicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Solicitud|null find($id, $lockMode = null, $lockVersion = null)
 * @method Solicitud|null findOneBy(array $criteria, array $orderBy = null)
 * @method Solicitud[]    findAll()
 * @method Solicitud[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Solicitud::class);
    }

public function findSolicitudByEstado(string $estado): array
{
        $qb = $this->createQueryBuilder('s')
            ->select('s.id, s.noDocumentoPrimario, s.fechaDocumento, s.fechaSolicitud')
            ->addSelect('s.objetivo, s.importeCup, s.importeCuc, s.importeTotal')
            ->addSelect('s.documentoPrimario, s.observacion, s.fechaModificacion')
            ->addSelect('c.id AS contratoId, c.numero AS contratoNumero')
            ->addSelect('pc.nombre AS proveedorCliente')
            ->addSelect('e.estado')
            ->addSelect('a.acapite')
            ->addSelect("CONCAT(u.nombre, ' ', u.apellidos) AS usuario")
            ->addSelect('un.nombre AS unidad' )
            ->addSelect('tp.tipo')
            ->addSelect('ip.instrumento')
            ->addSelect('td.tipo AS tipoDocumento')
            ->leftJoin('s.contrato', 'c')
            ->leftJoin('c.proveedorCliente', 'pc')
            ->leftJoin('s.estado', 'e')
            ->leftJoin('s.tipoPago', 'tp')
            ->leftJoin('s.tipoDocumento', 'td')
            ->leftJoin('s.instrumentoPago', 'ip')
            ->leftJoin('s.acapite', 'a')
            ->leftJoin('s.usuario', 'u')
            ->leftJoin('u.unidad', 'un')
            ->where('e.estado = :estado')
            ->setParameter('estado', $estado)
        ;

        return $qb->getQuery()
            ->getScalarResult();
    }

    public function findById(int $id): ?Solicitud
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s, c, e, pc, ec, tp, td, ip, a, u, un, ec')
            ->leftJoin('s.contrato', 'c')
            ->leftJoin('c.proveedorCliente', 'pc')
            ->leftJoin('c.estado', 'ec')
            ->leftJoin('s.estado', 'e')
            ->leftJoin('s.tipoPago', 'tp')
            ->leftJoin('s.tipoDocumento', 'td')
            ->leftJoin('s.instrumentoPago', 'ip')
            ->leftJoin('s.acapite', 'a')
            ->leftJoin('s.usuario', 'u')
            ->leftJoin('u.unidad', 'un')
            ->where('s.id = :id')
            ->setParameter('id', $id)
        ;

        return $qb->getQuery()
            ->getSingleResult();
    }

    public function findTotalPagosByUEBAndRango(string $inicio, string $fin): array
    {
        $qb = $this->createQueryBuilder('sp');

        return $qb->select('u.nombre AS unidad')
            ->addSelect("SUM(( CASE WHEN( t.tipo = 'PAGO POSTERIOR' ) THEN sp.importeTotal ELSE 0 END ) AS pago_posterior")
            ->addSelect("SUM(( CASE WHEN( t.tipo = 'PAGO ANTICIPADO' ) THEN sp.importeTotal ELSE 0 END ) AS pago_anticipado")
            ->addSelect("SUM(( CASE WHEN( t.tipo = 'OPERACIONES' ) THEN sp.importeTotal ELSE 0 END ) AS pago_operaciones")
            ->addSelect("SUM(( CASE WHEN( t.tipo = 'INVERSIONES' ) THEN sp.importeTotal ELSE 0 END ) AS pago_inversiones")
            ->leftJoin('sp.estado', 'e')
            ->leftJoin('sp.tipoPago', 't')
            ->leftJoin('sp.contrato', 'c')
            ->leftJoin('c.procedencia', 'u')
            ->where('e.estado = :estado')
            ->andWhere($qb->expr()->between('sp.fechaModificacion', ':inicio', ':fin'))
            ->setParameter('estado', 'PAGADO')
            ->setParameter('inicio', $inicio)
            ->setParameter('fin', $fin)
            ->groupBy('u.nombre')
            ->orderBy('u.nombre')
            ->getQuery()
            ->useQueryCache(true)
            ->getScalarResult();
    }

    public function findCicloPagosByUEB(): array
    {
        return $this->createQueryBuilder('sp')
            ->select('u.nombre AS unidad')
            ->addSelect("AVG(DATE_DIFF(sp.fechaModificacion, sp.fechaSolicitud)) AS estimado")
            ->leftJoin('sp.estado', 'e')
            ->leftJoin('sp.contrato', 'c')
            ->leftJoin('c.procedencia', 'u')
            ->where('e.estado = :estado')
            ->setParameter('estado', 'PAGADO')
            ->groupBy('u.nombre')
            ->orderBy('u.nombre')
            ->getQuery()
            ->useQueryCache(true)
            ->getScalarResult();
    }

    /* Reportes */

    public function findTotalesByEstado(): array
    {
        $reporte = $this->createQueryBuilder('s')
            ->select("SUM(( CASE WHEN( e.estado = 'PENDIENTE' )  THEN 1 ELSE 0 END )) AS pendientes")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'PAGADO' )  THEN 1 ELSE 0 END )) AS pagadas")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'REVISADO' )  THEN 1 ELSE 0 END )) AS en_revision")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'APROBADAS' )  THEN 1 ELSE 0 END )) AS aprobadas")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'NO APROBADAS' )  THEN 1 ELSE 0 END )) AS no_aprobadas")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'CANCELADO' )  THEN 1 ELSE 0 END )) AS canceladas")
            ->leftJoin('s.estado', 'e')
            ->getQuery()
            ->useQueryCache(true)
            ->getArrayResult();

        return $reporte[0];
    }
}
