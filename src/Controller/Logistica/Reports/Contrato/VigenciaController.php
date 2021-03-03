<?php

namespace App\Controller\Logistica\Reports\Contrato;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Home controller.
 *
 * @Route("logistica/reporte/contratos")
 */
class VigenciaController extends AbstractController
{
    /**
     * @Route("/vigencia-de/{start}/a/{end}/dias",
     *      name="app_reports_logistica_contrato_vigencia",
     *      requirements={"start":"1|31|61", "end":"30|60|90"},
     *      methods={"GET"}
     * )
     * @Route("/vencidos",
     *      name="app_reports_logistica_contrato_vencido",
     *      methods={"GET"}
     * )
     */
    public function index(EntityManagerInterface $em, int $start = 0, int $end = 0): Response
    {
        $contratos = $em->createQueryBuilder('c')
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
            ->leftJoin('c.vigencia', 'v');

        if($start === 0){
            $contratos->where('DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) <= 0');
        }

        if($start > 0){
            $contratos->where('DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) >= :start AND DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) <= :end')
                        ->setParameter('start', $start)
                        ->setParameter('end', $end);
        }

        return $this->render('logistica/reports/contrato/vigencia.html.twig', [
            'contratos' => $contratos->getQuery()->getResult(),
            'start' => $start,
            'end' => $end,
        ]);
    }
}
