<?php

namespace App\Controller\Logistica\Report\Contrato;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("logistica/reportes")
 */
class AprobadoController extends AbstractController
{
    protected $em;

    public function __construct (EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/contratos/aprobado-de/{start}/a/{end}/dias",
     *      name="report_logistica_contrato_aprobado",
     *      requirements={"start":"0|46|91", "end":"0|45|90|mas"},
     *      methods={"GET"}
     * )
     */
    public function index(int $start = 0, $end): Response
    {
        return $this->render('logistica/report/contrato/aprobado.html.twig', [
            'contratos' => $this->query($start, $end),
            'start' => $start,
            'end' => $end,
        ]);
    }

    /*
    *   Report Query
    */
    private function query(int $start = 0, $end): ?array
    {
        $range = "DATE_DIFF(CURRENT_DATE(), c.fechaAprobado ) >= :start AND DATE_DIFF(CURRENT_DATE(), c.fechaAprobado ) <= :end AND e.estado = 'APROBADO'";

        if ($end === 'mas'){
            $range = "DATE_DIFF(CURRENT_DATE(), c.fechaAprobado ) > :start AND DATE_DIFF(CURRENT_DATE(), c.fechaAprobado ) > :end AND e.estado = 'APROBADO'";
        }

        return $this->em->createQueryBuilder('c')
                ->select('c.id, c.numero, c.tipo, cat.categoria AS categoria, p.nombre AS procedencia')
                ->addSelect('cs.nombre AS proveedor, v.vigencia AS vigencia, e.estado AS estado')
                ->addSelect('c.fechaVigencia, c.fechaFirma, c.fechaAprobado')
                ->addSelect('(DATE_DIFF(c.fechaVigencia, CURRENT_DATE())) AS diasVigencia')
                ->addSelect('c.observacion')
                ->from('App:Logistica\Contrato\Contrato', 'c')
                ->join('c.proveedorCliente', 'cs')
                ->leftJoin('c.categoria', 'cat')
                ->leftJoin('c.procedencia', 'p')
                ->leftJoin('c.estado', 'e')
                ->leftJoin('c.vigencia', 'v')
                ->where($range)
                ->setParameter('start', $start)
                ->setParameter('end', $end === 'mas' ? $start : $end)
                ->getQuery()
                ->getResult();
    }
}
