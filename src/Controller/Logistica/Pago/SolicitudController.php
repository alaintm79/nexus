<?php

namespace App\Controller\Logistica\Pago;

use App\Controller\Logistica\Traits\HasMontoTrait;
use App\Entity\Logistica\Pago\Estado;
use App\Entity\Logistica\Pago\Solicitud;
use App\Form\Logistica\Pago\PagoType;
use App\Form\Logistica\Pago\SolicitudType;
use App\Repository\Logistica\Pago\SolicitudRepository;
use App\Service\FileUploader;
use App\Service\Notify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * SolicitudesPago controller.
 *
 * @Route("logistica/pagos")
 *
 */
class SolicitudController extends AbstractController
{
    use HasMontoTrait;

    /**
     * Lists all Solicitudes Pagos entities by Estado.
     *
     * @Route("/estado/{state}",
     *      name="app_pago_solicitud_index",
     *      defaults={"state": "pendiente"},
     *      requirements={"state": "pendiente|revisado|aprobado|no-aprobado|pagado|cancelado"},
     *      methods={"GET"}
     * )
     */
    public function index(string $state): Response
    {
        $titles = [
            'pendiente' => 'Pendientes',
            'revisado' => 'Revisadas',
            'aprobado' => 'Aprobadas',
            'no-aprobado' => 'No aprobadas',
            'pagado' => 'Pagadas',
            'cancelado' => 'Canceladas',
        ];

        return $this->render('logistica/pago/solicitud.html.twig', [
            'state' => $state,
            'title' => $titles[$state]
        ]);
    }

    /**
     * @Route("/estado/{state}/list",
     *      name="app_pago_solicitud_list",
     *      defaults={"state": "pendiente"},
     *      requirements={"state": "pendiente|revisado|aprobado|no-aprobado|pagado|cancelado"},
     *      methods={"GET"}
     * )
     */
    public function list (SolicitudRepository $solicitudes, string $state): Response
    {
        return new JsonResponse($solicitudes->findSolicitudByEstado(\str_replace('-', ' ', \strtoupper($state))));
    }

    /**
     * Creates a new Solicitud entity.
     *
     * @Route("/new",
     *      name="app_pago_pendiente_new",
     *      methods={"GET", "POST"}
     * )
     */
    // $notify->send($this->getParameter('app_notify_finanzas'), $solicitud, 'logistica/pago/notificacion.html.twig', 'Nueva solicitud de pago generada');
    public function create(Request $request/* , Notify $notify */): Response
    {
        $solicitud = new Solicitud();
        $form = $this->createForm(SolicitudType::class, $solicitud);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!$this->hasMontoEjecucion($solicitud)){
                return $this->renderSolicitud($solicitud, $form, 'create', true);
            }

            $em = $this->getDoctrine()->getManager();
            $estado = $em->getRepository(Estado::class)->findOneBy(['estado' => 'PENDIENTE']);

            $solicitud->setUsuario($this->getUser());
            $solicitud->setEstado($estado);

            if(null !== $solicitud->getDocumentoPrimario()){
                $this->uploadDocumento($solicitud, $form, 'fileDocumentoPrimario');
            }

            $this->addFlash('notice', 'Solicitud de pago registrada con exito!');

            $em->persist($solicitud);
            $em->flush();


            return $this->render('common/notify.html.twig', []);
        }

        return $this->renderSolicitud($solicitud, $form, 'create');
    }

    /**
     * Displays a form to edit an existing Solicitud entity.
     *
     * @Route("/{sp_id<[1-9]\d*>}/edit",
     *      name="app_pago_pendiente_edit",
     *      methods={"GET", "POST"}
     * )
     * @Entity("solicitud", expr="repository.findById(sp_id)")
     */
    public function edit(Request $request, Solicitud $solicitud): Response
    {
        $form = $this->createForm(SolicitudType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(null !== $form->get('fileDocumentoPrimario')->getData()){
               $this->uploadDocumento($solicitud, $form, 'fileDocumentoPrimario');
            }

            $this->addFlash('notice', 'Solicitud de pago modificada con exito!');
            $this->getDoctrine()->getManager()->flush();

            return $this->render('common/notify.html.twig', []);
        }

        return $this->renderSolicitud($solicitud, $form, 'edit');
    }

    /**
     * Finds and displays a Solicitud entity.
     *
     * @Route("/{solicitud_id<[1-9]\d*>}/show", name="app_pago_pendiente_show")
     * @Entity("solicitud", expr="repository.findById(solicitud_id)")
     */
    public function show(Solicitud $solicitud): Response
    {
        return $this->render('logistica/pago/modal/solicitud_show.html.twig', [
            'solicitud' => $solicitud,
        ]);
    }

    /**
     * Render View personalizados
     */
    private function renderSolicitud(Solicitud $solicitud, $form, $action, $error = null)
    {
        return $this->render('logistica/pago/modal/solicitud_form.html.twig', [
            'solicitud' => $solicitud,
            'form' => $form->createView(),
            'action' => $action,
            'error' => $error !== null ? 'No es posible generar la solicitud de pago, importe del contrato insuficiente!' : null
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
        $nombreDocumento = $solicitud->getNoDocumentoPrimario().'-'.$solicitud->getTipoDocumento();
        $documento = $fileUploader->upload($file, $path, $nombreDocumento, false);

        $solicitud->setDocumentoPrimario($documento);
    }
}
