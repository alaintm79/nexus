<?php

namespace App\Controller\Contacto;

use App\Entity\Contacto\Ubicacion;
use App\Form\Contacto\UbicacionType;
use App\Repository\Contacto\UbicacionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Ubicacion controller.
 *
 * @Route("contactos/ubicacion")
 */
class UbicacionController extends AbstractController
{
    /**
     * @Route("/",
     *      name="app_contacto_ubicacion_index",
     *      methods={"GET"}
     * )
     */
    public function index(): Response
    {
        $breadcrumb = [
            ['title' => 'Contactos', 'url' => $this->generateUrl('app_contacto_index')],
            ['title' => 'Ubicación']
        ];

        return $this->render('contacto/ubicacion.html.twig', [
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/list",
     *      name="app_contacto_ubicacion_list",
     *      methods={"GET"}
     * )
     */
    public function list(UbicacionRepository $ubicacion): Response
    {
        return new JsonResponse($ubicacion->findAll());
    }

    /**
     * Creates a new ubicacion entity.
     *
     * @Route("/new",
     *      name="app_contacto_ubicacion_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request): Response
    {
        $ubicacion = new Ubicacion();
        $form = $this->createForm(UbicacionType::class, $ubicacion);
        $breadcrumb = [
            ['title' => 'Contactos', 'url' => $this->generateUrl('app_contacto_index')],
            ['title' => 'Ubicación', 'url' => $this->generateUrl('app_contacto_ubicacion_index')],
            ['title' => 'Registrar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($ubicacion);
            $em->flush();

            $this->addFlash('notice', 'Ubicación registrada con exito!');

            if ($form->get('saveAndReturn')->isClicked()) {
                return $this->redirectToRoute('app_contacto_ubicacion_new');
            }

            return $this->redirectToRoute('app_contacto_ubicacion_index');
        }

        return $this->render('contacto/form/ubicacion_form.html.twig', [
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Displays a form to edit an existing ubicacion entity.
     *
     * @Route("/{id<[1-9]\d*>}/edit",
     *      name="app_contacto_ubicacion_edit",
     *      methods={"GET", "POST"})
     */
    public function edit(Request $request, Ubicacion $ubicacion): Response
    {
        $form = $this->createForm(UbicacionType::class, $ubicacion);
        $breadcrumb = [
            ['title' => 'Contactos', 'url' => $this->generateUrl('app_contacto_index')],
            ['title' => 'Ubicación', 'url' => $this->generateUrl('app_contacto_ubicacion_index')],
            ['title' => 'Registrar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'Ubicación modificada con exito!');

            return $this->redirectToRoute('app_contacto_ubicacion_index');
        }

        return $this->render('contacto/form/ubicacion_form.html.twig', [
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }
}
