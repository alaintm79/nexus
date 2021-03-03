<?php

namespace App\Controller\Logistica\Reports\Contrato;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Home controller.
 *
 * @Route("logistica/reporte/contratos")
 */
class TramitacionController extends AbstractController
{
    /**
     * @Route("/tramitacion/",
     *      name="app_reports_logistica_contrato_tramitacion",
     *      methods={"GET"}
     * )
     */
    public function index(EntityManagerInterface $em): Response
    {
        $tiempos = $em->createQueryBuilder('c')
            ->select('u.nombre AS unidad')
            ->addSelect("AVG(DATE_DIFF(c.fechaFirma, c.fechaAprobado)) AS estimado")
            ->from('App:Logistica\Contrato\Contrato', 'c')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('c.procedencia', 'u')
            ->where('c.tipo = :tipo')
            ->andWhere('e.estado = :estado')
            ->setParameter('tipo', 'p')
            ->setParameter('estado', 'FIRMADO')
            ->groupBy('u.nombre')
            ->orderBy('u.nombre')
            ->getQuery()
            ->useQueryCache(true)
            ->getScalarResult();

        return $this->render('logistica/reports/contrato/tramitacion.html.twig', [
            'tiempos' => $tiempos,
        ]);
    }
}
