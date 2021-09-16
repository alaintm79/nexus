<?php

namespace App\Controller\Contacto;

use App\Entity\Contacto\Contacto;
use App\Entity\Contacto\Perfil;
use App\Entity\Contacto\Ubicacion;
use App\Form\Contacto\ContactoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\Contacto\ContactoRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Contacto controller.
 *
 * @Route("contactos")
 */
class ContactoController extends AbstractController
{
    /**
     * @Route("/",
     *      name="app_contacto_index",
     *      methods={"GET"}
     * )
     */
    public function index(): Response
    {
        $breadcrumb = [
            ['title' => 'Contactos']
        ];

        return $this->render('contacto/contacto.html.twig', [
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/list",
     *      name="app_contacto_list",
     *      methods={"GET"}
     * )
     */
    public function list(ContactoRepository $contactos): Response
    {
        return new JsonResponse($contactos->findAll());
    }

    /**
     * Creates a new contacto entity.
     *
     * @Route("/new",
     *      name="app_contacto_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request): Response
    {
        $contacto = new Contacto();
        $form = $this->createForm(ContactoType::class, $contacto);
        $breadcrumb = [
            ['title' => 'Contactos', 'url' => $this->generateUrl('app_contacto_index')],
            ['title' => 'Registrar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($contacto);
            $em->flush();

            $this->addFlash('notice', 'Contacto registrado con exito!');

            if ($form->get('saveAndReturn')->isClicked()) {
                return $this->redirectToRoute('app_contacto_new');
            }

            return $this->redirectToRoute('app_contacto_index');
        }

        return $this->render('contacto/form/contacto_form.html.twig', [
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Displays a form to edit an existing contacto entity.
     *
     * @Route("/{id<[1-9]\d*>}/edit",
     *      name="app_contacto_edit",
     *      methods={"GET", "POST"}
     * )
     */
    public function edit(Request $request, Contacto $contacto): Response
    {
        $form = $this->createForm(ContactoType::class, $contacto);
        $breadcrumb = [
            ['title' => 'Contactos', 'url' => $this->generateUrl('app_contacto_index')],
            ['title' => 'Modificar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'Contacto modificado con exito!');

            return $this->redirectToRoute('app_contacto_index');
        }

        return $this->render('contacto/form/contacto_form.html.twig', [
            'form' => $form->createView(),
            'contacto' => $contacto,
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Finds and displays a contacto entity.
     *
     * @Route("/{id<[1-9]\d*>}/show",
     *      name="app_contacto_show",
     *      methods={"GET"}
     * )
     */
    public function show(Contacto $contacto): Response
    {
        $breadcrumb = [
            ['title' => 'Contactos', 'url' => $this->generateUrl('app_contacto_index')],
            ['title' => 'Detalle']
        ];

        return $this->render('contacto/form/contacto_show.html.twig', [
            'contacto' => $contacto,
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Deletes a Contacto entity.
     *
     * @Route("/{id<[1-9]\d*>}/delete",
     *      name="app_contacto_delete",
     *      methods={"GET"}
     * )
     */
    public function delete(Request $request, Contacto $contacto): Response
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contacto);
            $em->flush();

            $this->addFlash('notice', 'Contacto eliminado con exito!');

            return $this->redirectToRoute('app_contacto_index');
    }
}

