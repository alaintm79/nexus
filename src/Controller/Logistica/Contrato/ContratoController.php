<?php

namespace App\Controller\Logistica\Contrato;

use App\Entity\Logistica\Contrato\Contrato;
use App\Entity\Logistica\Contrato\Estado;
use App\Form\Logistica\Contrato\ContratoType;
use App\Repository\Logistica\Contrato\ContratoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Contrato controller.
 *
 * @Route("logistica/contrato")
 *
 */
class ContratoController extends AbstractController
{
    /**
     * Lists all contrato entities.
     *
     * @Route("/{tipo}",
     *      name="app_contrato_index",
     *      requirements={"tipo": "proveedor|cliente"},
     *      methods={"GET"}
     * )
     */
    public function index(string $tipo): Response
    {
        return $this->render('logistica/contrato/index.html.twig', [
            'tipo' => $tipo
        ]);
    }

    /**
     * @Route("/{tipo}/list",
     *      name="app_contrato_list",
     *      requirements={"tipo": "proveedor|cliente"},
     *      methods={"GET"}
     * )
     */
    public function list(ContratoRepository $contratos, string $tipo): Response
    {
        return new JsonResponse($contratos->findContratosByTipo($tipo));
    }

    /**
     * Creates a new contrato entity.
     *
     * @Route("/{tipo}/new",
     *      name="app_contrato_new",
     *      requirements={"tipo": "proveedor|cliente"},
     *      methods={"GET","POST"}
     * )
     */
    public function new(Request $request, string $tipo): Response
    {
        $options = ['tipo' => $tipo];
        $contrato = new Contrato();
        $form = $this->createForm(ContratoType::class, $contrato, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $estado = $em->getRepository(Estado::class) ->findOneBy(['estado' => 'REVISION']);
            $contrato->setTipo($tipo);
            $contrato->setEstado($estado);

            $this->addFlash('notice', 'Contrato de '.$tipo.' registrado con exito!');

            $em->persist($contrato);
            $em->flush();

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('logistica/contrato/modal/contrato_form.html.twig', [
            'form' => $form->createView(),
            'contrato' => $contrato,
        ]);
    }

    /**
     * Displays a form to edit an existing contrato entity.
     *
     * @Route("/{tipo}/{id<[1-9]\d*>}/edit",
     *      name="app_contrato_edit",
     *      requirements={"tipo": "proveedor|cliente"},
     *      methods={"GET", "POST"}
     * )
     */
    public function edit(Request $request, Contrato $contrato, string $tipo): Response
    {
        $options = ['tipo' => $tipo];
        $form = $this->createForm(ContratoType::class, $contrato, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('notice', 'Contrato de '.$tipo.' modificado con exito!');
            $this->getDoctrine()->getManager()->flush();

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('logistica/contrato/modal/contrato_form.html.twig', [
            'form' => $form->createView(),
            'contrato' => $contrato,
        ]);
    }

    /**
     * Finds and displays a contrato entity.
     *
     * @Route("/{tipo}/{contrato_id<[1-9]\d*>}/show",
     *      name="app_contrato_show",
     *      requirements={"tipo": "proveedor|cliente"},
     *      methods={"GET"}
     * )
     * @Entity("contrato", expr="repository.findById(contrato_id)")
     */
    public function show(Contrato $contrato): Response
    {
        return $this->render('logistica/contrato/modal/contrato_show.html.twig', [
            'contrato' => $contrato,
        ]);
    }

    /**
     * Reporte del dashboard
     */
    public function reporteDashboard(): Response
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('logistica/contrato/_dashboard.html.twig', [
            'contratos' => $em->getRepository(Contrato::class)->findTotalByEstado(),
        ]);
    }
}
