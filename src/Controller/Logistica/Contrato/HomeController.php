<?php

namespace App\Controller\Logistica\Contrato;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Home controller.
 *
 * @Route("logistica/contratos")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="contrato_home")
     */
    public function indexAction (): Response
    {
        return $this->render('logistica/contrato/home/index.html.twig', [
            'reporte' => $this->getDoctrine()->getRepository('AppBundle:Contratos\Contrato')->getTotales(),
            // 'alertas_avisos' => $this->getDoctrine()->getRepository('AppBundle:Contratos\Contrato')->getAlertasAndAvisos(),
        ]);
    }
}
