<?php

namespace App\Controller\Contacto;

use App\Entity\Contacto\Perfil;
use App\Form\Contacto\PerfilType;
use App\Repository\Contacto\PerfilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Perfil controller.
 *
 * @Route("contactos/perfil")
 */
class PerfilController extends AbstractController
{
    /**
     * @Route("/",
     *      name="app_contacto_perfil_index",
     *      methods={"GET"}
     * )
     */
    public function index(): Response
    {
        $breadcrumb = [
            ['title' => 'Perfil profesional']
        ];

        return $this->render('contacto/perfil.html.twig', [
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/list",
     *      name="app_contacto_perfil_list",
     *      methods={"GET"}
     * )
     */
    public function list(PerfilRepository $perfil): Response
    {
        return new JsonResponse($perfil->findAll());
    }

    /**
     * Creates a new perfil entity.
     *
     * @Route("/new",
     *      name="app_contacto_perfil_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request): Response
    {
        $perfil = new Perfil();
        $form = $this->createForm(PerfilType::class, $perfil);
        $breadcrumb = [
            ['title' => 'Perfil profesional', 'url' => $this->generateUrl('app_contacto_perfil_index')],
            ['title' => 'Registrar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($perfil);
            $em->flush();

            $this->addFlash('notice', 'Perfil registrado con exito!');

            if ($form->get('saveAndReturn')->isClicked()) {
                return $this->redirectToRoute('app_contacto_perfil_new');
            }

            return $this->redirectToRoute('app_contacto_perfil_index');
        }

        return $this->render('contacto/form/perfil_form.html.twig', [
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Displays a form to edit an existing perfil entity.
     *
     * @Route("/{id<[1-9]\d*>}/edit",
     *      name="app_contacto_perfil_edit",
     *      methods={"GET", "POST"}
     * )
     */
    public function edit(Request $request, Perfil $perfil): Response
    {
        $form = $this->createForm(PerfilType::class, $perfil);
        $breadcrumb = [
            ['title' => 'Perfil profesional', 'url' => $this->generateUrl('app_contacto_perfil_index')],
            ['title' => 'Registrar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'Perfil modificado con exito!');

            return $this->redirectToRoute('app_contacto_perfil_index');
        }

        return $this->render('contacto/form/perfil_form.html.twig', [
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }
}
