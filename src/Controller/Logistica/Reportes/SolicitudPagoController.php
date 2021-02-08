<?php

namespace App\Controller\Logistica\Reportes;

use App\Repository\Logistica\SolicitudPago\SolicitudPagoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Home controller.
 *
 * @Route("logistica/reportes/solicitudes-pagos")
 * @Security("is_granted(['ROLE_COMERCIAL', 'ROLE_FINANZAS', 'ROLE_DIRECTOR'])")
 */
class SolicitudPagoController extends AbstractController
{
    /**
     * @Route("/",
     *      name="reportes_solicitud_pago_index",
     *      methods={"GET"}
     * )
     */
    public function index ()
    {
        return $this->render('logistica/reportes/solicitudes_pagos/index.html.twig');
    }

    /**
     * @Route("/periodo/",
     *      name="reportes_solicitud_pago_periodo",
     *      methods={"GET|POST"}
     * )
     */
    public function periodo(Request $request, SolicitudPagoRepository $solicitudes)
    {
        $form = $this->createFormBuilder([])
            ->add('start', TextType::class, [
                'label' => 'Fecha Inicial',
            ])
            ->add('end', TextType::class, [
                'label' => 'Fecha Final',
            ])
            ->add('estado', EntityType::class, [
                'class' => 'App\Entity\Logistica\SolicitudPago\Estado',
                'required' => false
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $data = $form->getData();
            $solicitudes = $solicitudes->findSolicitudPagoByRango($data['start'], $data['end'], $data['estado']);
        }

        return $this->render('logistica/reportes/solicitudes_pagos/periodo.html.twig', [
            'form' => $form->createView(),
            'solicitudes' => $solicitudes
        ]);
    }

    /**
     * @Route("/ciclo-pago/",
     *      name="reportes_solicitud_pago_ciclo",
     *      methods={"GET"}
     * )
     */
    public function tramitacion(SolicitudPagoRepository $solicitudes)
    {
        return $this->render('logistica/reportes/contrato/tramitacion.html.twig', [
            'tiempos' => $solicitudes->findCicloPagosByUEB(),
            'reporte' => 'Comportamiento del Ciclo de Pagos'
        ]);
    }

    /**
     * @Route("/total-pagos/",
     *      name="reportes_solicitud_pago_total",
     *      methods={"GET|POST"}
     * )
     */
    public function total(Request $request, SolicitudPagoRepository $solicitudes)
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
