<?php

namespace App\Controller\Logistica\Report\Contrato;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @Route("logistica/reportes")
 */
class HomeController extends AbstractController
{
    protected $em;

    public function __construct (EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/contratos/",
     *      name="report_logistica_contrato_home",
     *      methods={"GET"}
     * )
     */
    public function index(): Response
    {
        return $this->render('logistica/report/contrato/home.html.twig', [
            'alertas_avisos' => $this->query()[0],
        ]);
    }

    /*
    *   Report Query
    */
    private function query(): ?array
    {
        return $this->em->createQueryBuilder('c')
            ->select("SUM(( CASE WHEN( DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) <= 30 AND DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) >= 1 )  THEN 1 ELSE 0 END )) AS ctos_30")
            ->addSelect("SUM(( CASE WHEN( DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) <= 0 )  THEN 1 ELSE 0 END )) AS ctos_vencidos")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'REVISION' )  THEN 1 ELSE 0 END )) AS ctos_revision")
            ->addSelect("SUM(( CASE WHEN( DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) <= 60 AND DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) >= 31 )  THEN 1 ELSE 0 END )) AS ctos_60")
            ->addSelect("SUM(( CASE WHEN( DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) <= 90 AND DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) >= 61 )  THEN 1 ELSE 0 END )) AS ctos_90")
            ->addSelect("SUM(( CASE WHEN( DATE_DIFF(CURRENT_DATE(), c.fechaAprobado ) >= 90 AND e.estado = 'APROBADO' )  THEN 1 ELSE 0 END )) AS apdos_90")
            ->addSelect("SUM(( CASE WHEN( DATE_DIFF(CURRENT_DATE(), c.fechaAprobado ) >= 45 AND DATE_DIFF(CURRENT_DATE(), c.fechaAprobado ) < 90 AND e.estado = 'APROBADO' )  THEN 1 ELSE 0 END )) AS apdos_45")
            ->addSelect("SUM(( CASE WHEN( DATE_DIFF(CURRENT_DATE(), c.fechaAprobado ) < 45 AND e.estado = 'APROBADO' )  THEN 1 ELSE 0 END )) AS apdos_lt_45")
            ->addSelect("SUM(( CASE WHEN((c.valorEjecucionCup = 0) AND e.estado = 'FIRMADO' )  THEN 1 ELSE 0 END )) AS sin_ejecucion")
            ->addSelect("SUM(( CASE WHEN((c.valorEjecucionCup = 0) AND e.estado = 'FIRMADO' )  THEN 1 ELSE 0 END )) AS sin_ejecucion_cup")
            ->addSelect("SUM(( CASE WHEN((c.valorEjecucionCuc = 0) AND e.estado = 'FIRMADO' )  THEN 1 ELSE 0 END )) AS sin_ejecucion_cuc")
            ->from('App:Logistica\Contrato\Contrato', 'c')
            ->leftJoin('c.estado', 'e')
            ->getQuery()
            ->useQueryCache(true)
            ->getArrayResult();
    }
}
