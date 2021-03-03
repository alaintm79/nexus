<?php

namespace App\Controller\Logistica\Pago;

use App\Controller\Logistica\Traits\HasMontoTrait;
use App\Entity\Logistica\Contrato\Ejecucion;
use App\Entity\Logistica\Pago\Estado;
use App\Entity\Logistica\Pago\Solicitud;
use App\Repository\Logistica\Pago\SolicitudRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Estado controller.
 *
 * @Route("logistica/pagos")
 *
 */
class EstadoController extends AbstractController
{
    use HasMontoTrait;

    /**
     * Lists all SolicitudesPagos entities.
     *
     * @Route("/{sp_id<[1-9]\d*>}/estado/{state}",
     *      name="app_pago_state",
     *      requirements={"state": "revisado|aprobado|no-aprobado|pagado|cancelado"},
     *      methods={"GET"}
     * )
     * @Entity("solicitud", expr="repository.findById(sp_id)")
     */
    public function state(string $state, Solicitud $solicitud): Response
    {
        if(false === $this->hasMontoEjecucion($solicitud))
        {
            $this->addFlash('error', 'No existe monto de ejecución!');

            return $this->render('common/notify.html.twig', []);
        }

        $em = $this->getDoctrine()->getManager();
        $estado = $em->getRepository(Estado::class)->findOneBy(['estado' => \str_replace('-', ' ', \strtoupper($state))]);

        if($state === 'pagado')
        {
            $contrato = $solicitud->getContrato();

            $ejecucion = new Ejecucion();
            $ejecucion->setSolicitud($solicitud);

            if(null !== $solicitud->getImporteCup()){
                $montoCup = $contrato->getValorEjecucionCup() - $solicitud->getImporteCup();

                $ejecucion->setSaldoCup($montoCup);
                $contrato->setValorEjecucionCup($montoCup);
            }

            $em->persist($ejecucion);
        }

        $solicitud->setEstado($estado);

        $em->flush();

        $this->addFlash('notice', 'Cambio de estado realizado con exito!');

        return $this->render('common/notify.html.twig', []);
    }
}
