<?php

namespace App\Controller\Logistica\Pago;

use App\Entity\Logistica\Pago\Solicitud;
use App\Form\Logistica\Pago\PagoType;
use App\Repository\Logistica\Pago\SolicitudRepository;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * SolicitudesPago controller.
 *
 * @Route("logistica/pagos")
 *
 */
class PagoController extends AbstractController
{
    /**
     * Displays a form to edit an existing Solicitud entity.
     *
     * @Route("/{sp_id}/pago",
     *      name="app_pago_pago",
     *      methods={"GET", "POST"}
     * )
     * @Entity("solicitud", expr="repository.findById(sp_id)")
     */
    public function pago(Request $request, Solicitud $solicitud): Response
    {
        if('FACTURA' === $solicitud->getTipoDocumento()->getTipo()){
            return $this->redirectToRoute('app_pago_state', [
                'sp_id' => $solicitud->getId(),
                'state' => 'pagado'
            ]);
        }

        $form = $this->createForm(PagoType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(null !== $form->get('fileDocumentoSecundario')->getData()){
               $this->uploadDocumento($solicitud, $form, 'fileDocumentoSecundario');
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_pago_state', [
                'sp_id' => $solicitud->getId(),
                'state' => 'pagado'
            ]);
        }

        return $this->render('logistica/pago/modal/pago_form.html.twig', [
            'form' => $form->createView(),
            'solicitud' => $solicitud,
            'action' => 'edit',
        ]);
    }

    /**
     * Upload documento
     */
    private function uploadDocumento(Solicitud $solicitud, Form $form, string $field)
    {
        $fileUploader = new FileUploader();
        $file = $form->get($field)->getData();
        $path = '/assets/solicitudes/'.$solicitud->getContrato()->getNumero().'/';
        $nombreDocumento = $solicitud->getNoDocumentoSecundario().'-factura';
        $documento = $fileUploader->upload($file, $path, $nombreDocumento, false);

        $solicitud->setDocumentoSecundario($documento);
    }

    /**
     * Reporte del dashboard
     */
    public function reporteDashboard(): Response
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('logistica/pago/_dashboard.html.twig', [
            'solicitudes' => $em->getRepository(Solicitud::class)->findTotalesByEstado(),
        ]);
    }
}
