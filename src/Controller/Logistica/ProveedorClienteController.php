<?php

namespace App\Controller\Logistica;

use App\Entity\Logistica\ProveedorCliente;
use App\Form\Logistica\ProveedorClienteType;
use App\Repository\Logistica\ProveedorClienteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ProveedorCliente controller.
 *
 * @Route("logistica/proveedor-cliente")
 *
 */
class ProveedorClienteController extends AbstractController
{
    /**
     * Lists all ProveedorCliente entities.
     *
     * @Route("/",
     *      name="app_proveedor_cliente_index",
     *      methods={"GET"}
     * )
     */
    public function index(): Response
    {
        return $this->render('logistica/proveedor_cliente/index.html.twig', []);
    }

    /**
     * @Route("/list",
     *      name="app_proveedor_cliente_list",
     *      methods={"GET"}
     * )
     */
    public function list (ProveedorClienteRepository $proveedorCliente): Response
    {
        return new JsonResponse($proveedorCliente->findAll());
    }

    /**
     * Creates a new ProveedorCliente entity.
     *
     * @Route("/new",
     *      name="app_proveedor_cliente_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request): Response
    {
        $proveedorCliente = new ProveedorCliente;
        $form = $this->createForm(ProveedorClienteType::class, $proveedorCliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($proveedorCliente);
            $em->flush();

            $this->addFlash('notice', 'Proveedor / Cliente registrado con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('logistica/proveedor_cliente/modal/form.html.twig', [
            'form' => $form->createView(),
            'action' => 'create',
        ]);
    }

    /**
     * Displays a form to edit an existing ProveedorCliente entity.
     *
     * @Route("/{id}/edit",
     *      name="app_proveedor_cliente_edit",
     *      methods={"GET", "POST"}
     * )
     */
    public function edit(Request $request, ProveedorCliente $proveedorCliente): Response
    {
        $form = $this->createForm(ProveedorClienteType::class, $proveedorCliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'Proveedor / Cliente modificado con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('logistica/proveedor_cliente/modal/form.html.twig', [
            'form' => $form->createView(),
            'action' => 'edit',
        ]);
    }

    /**
     * Finds and displays a ProveedorCliente entity.
     *
     * @Route("/{id}/show",
     *      name="app_proveedor_cliente_show",
     *      methods={"GET"}
     * )
     */
    public function show(ProveedorCliente $proveedorCliente): Response
    {
        return $this->render('logistica/proveedor_cliente/modal/show.html.twig', [
            'proveedorCliente' => $proveedorCliente,
        ]);
    }
}
