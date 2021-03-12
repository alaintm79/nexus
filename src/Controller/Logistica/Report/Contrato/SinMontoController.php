<?php

namespace App\Controller\Logistica\Report\Contrato;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("logistica/reportes")
 */
class SinMontoController extends AbstractController
{
    protected $em;

    public function __construct (EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/contratos/sin-monto",
     *      name="report_logistica_contrato_monto",
     *      methods={"GET"}
     * )
     */
    public function index(): Response
    {
        return $this->render('logistica/report/contrato/sin_monto.html.twig', [
            'contratos' => $this->query(),
        ]);
    }

    /*
    *   Report Query
    */

    private function query(): ?array
    {
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
                ->where("c.valorEjecucionCup = 0 AND e.estado = 'FIRMADO'")
                ->getQuery()
                ->getResult();
    }
}
