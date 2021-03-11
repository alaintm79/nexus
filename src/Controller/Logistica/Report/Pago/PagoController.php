<?php

namespace App\Controller\Logistica\Report\Pago;

use App\Repository\Logistica\Pago\SolicitudRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Home controller.
 *
 * @Route("logistica/reportes/pagos")
 */
class PagoController extends AbstractController
{

    /**
     * @Route("/contratos/total-pagos/",
     *      name="reporte_pago_total",
     *      methods={"GET|POST"}
     * )
     */
    public function total(Request $request, SolicitudRepository $solicitudes): Response
    {
        $form = $this->createFormBuilder([])
            ->add('start', TextType::class, [
                'label' => 'Fecha Inicial',
            ])
            ->add('end', TextType::class, [
                'label' => 'Fecha Final',
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $fecha = $form->getData();
            $solicitudes = $solicitudes->findTotalPagosByUEBAndRango($fecha['start'], $fecha['end']);
        }

        return $this->render('logistica/reportes/solicitudes_pagos/total.html.twig', [
            'form' => $form->createView(),
            'pagos' => $solicitudes,
            'reporte' => 'Total de pagos en el periodo'
        ]);
    }
}
