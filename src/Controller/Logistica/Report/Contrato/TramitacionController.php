<?php

namespace App\Controller\Logistica\Report\Contrato;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("logistica/reportes")
 */
class TramitacionController extends AbstractController
{
    protected $em;

    public function __construct (EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/contratos/tramitacion/",
     *      name="report_logistica_contrato_tramitacion",
     *      methods={"GET"}
     * )
     */
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('logistica/report/contrato/tramitacion.html.twig', [
            'tiempos' => $this->query(),
        ]);
    }

    /*
    *   Report Query
    */

    private function query(): ?array
    {
        return $this->em->createQueryBuilder('c')
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
    }
}
