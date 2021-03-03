<?php

namespace App\Controller\Logistica\Reports\Pago;

use App\Entity\Logistica\Pago\Estado;
use App\Repository\Logistica\Pago\SolicitudRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Home controller.
 *
 * @Route("logistica/reportes/pagos")
 * @Security("is_granted(['ROLE_COMERCIAL', 'ROLE_FINANZAS', 'ROLE_DIRECTOR'])")
 */
class PagoController extends AbstractController
{
    /**
     * @Route("/",
     *      name="reporte_pago_index",
     *      methods={"GET"}
     * )
     */
    public function index (): Response
    {
        return $this->render('logistica/reportes/solicitudes_pagos/index.html.twig');
    }

    /**
     * @Route("/periodo/",
     *      name="reporte_pago_periodo",
     *      methods={"GET|POST"}
     * )
     */
    public function periodo(Request $request, SolicitudRepository $solicitudes): Response
    {
        $form = $this->createFormBuilder([])
            ->add('start', TextType::class, [
                'label' => 'Fecha Inicial',
            ])
            ->add('end', TextType::class, [
                'label' => 'Fecha Final',
            ])
            ->add('estado', EntityType::class, [
                'class' => Estado::class,
                'required' => false
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $data = $form->getData();
            $solicitudes = $solicitudes->findSolicitudByRango($data['start'], $data['end'], $data['estado']);
        }

        return $this->render('logistica/reportes/solicitudes_pagos/periodo.html.twig', [
            'form' => $form->createView(),
            'solicitudes' => $solicitudes
        ]);
    }

    /**
     * @Route("/ciclo-pago/",
     *      name="reporte_pago_ciclo",
     *      methods={"GET"}
     * )
     */
    public function tramitacion(SolicitudRepository $solicitudes): Response
    {
        return $this->render('logistica/reportes/contrato/tramitacion.html.twig', [
            'tiempos' => $solicitudes->findCicloPagosByUEB(),
            'reporte' => 'Comportamiento del Ciclo de Pagos'
        ]);
    }

    /**
     * @Route("/total-pagos/",
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
