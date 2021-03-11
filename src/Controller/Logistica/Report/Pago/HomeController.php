<?php

namespace App\Controller\Logistica\Report\Pago;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("logistica/reportes")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/pagos/",
     *      name="report_logistica_pago_home",
     *      methods={"GET"}
     * )
     */
    public function index(): Response
    {
        return $this->render('logistica/report/pago/home.html.twig');
    }
}
