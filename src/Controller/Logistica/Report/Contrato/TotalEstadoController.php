<?php

namespace App\Controller\Logistica\Report\Contrato;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("logistica/reportes")
 */
class TotalEstadoController extends AbstractController
{
    protected $em;

    public function __construct (EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/contratos/totales",
     *      name="report_logistica_contrato_totales",
     *      methods={"GET"}
     * )
     */
    public function index(): Response
    {
        return $this->render('logistica/report/contrato/total_estado.html.twig', [
            'contratos' => $this->query(),
        ]);
    }

    /*
    *   Report Query
    */

    private function query(): ?array
    {
        return $this->em->createQueryBuilder('c')
            ->select('u.nombre AS unidad')
            ->addSelect("SUM(( CASE WHEN( e.estado = 'FIRMADO' AND c.tipo = 'p' ) THEN 1 ELSE 0 END )) AS firmados_proveedor")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'FIRMADO' AND c.tipo = 'c' ) THEN 1 ELSE 0 END )) AS firmados_cliente")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'APROBADO' AND c.tipo = 'p' ) THEN 1 ELSE 0 END )) AS aprobados_proveedor")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'APROBADO' AND c.tipo = 'c' ) THEN 1 ELSE 0 END )) AS aprobados_cliente")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'REVISION' AND c.tipo = 'p' ) THEN 1 ELSE 0 END )) AS revision_proveedor")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'REVISION' AND c.tipo = 'c' ) THEN 1 ELSE 0 END )) AS revision_cliente")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'CANCELADO' AND c.tipo = 'p' ) THEN 1 ELSE 0 END )) AS cancelados_proveedor")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'CANCELADO' AND c.tipo = 'c' ) THEN 1 ELSE 0 END )) AS cancelados_cliente")
            ->from('App:Logistica\Contrato\Contrato', 'c')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('c.procedencia', 'u')
            ->groupBy('u.nombre')
            ->orderBy('u.nombre')
            ->getQuery()
            ->useQueryCache(true)
            ->getScalarResult();
    }
}
