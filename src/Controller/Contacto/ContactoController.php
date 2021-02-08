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
        return $this->render('contacto/contacto.html.twig');
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($contacto);
            $em->flush();

            $this->addFlash('notice', 'Contacto registrado con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('contacto/modal/contacto_form.html.twig', [
            'form' => $form->createView(),
            'action' => 'create'
        ]);
    }

    /**
     * Displays a form to edit an existing contacto entity.
     *
     * @Route("/{id}/edit",
     *      name="app_contacto_edit",
     *      methods={"GET", "POST"}
     * )
     */
    public function edit(Request $request, Contacto $contacto): Response
    {
        $form = $this->createForm(ContactoType::class, $contacto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'Contacto modificado con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('contacto/modal/contacto_form.html.twig', [
            'form' => $form->createView(),
            'contacto' => $contacto,
            'action' => 'edit'
        ]);
    }

    /**
     * Finds and displays a contacto entity.
     *
     * @Route("/{id}/show",
     *      name="app_contacto_show",
     *      methods={"GET"}
     * )
     */
    public function show(Contacto $contacto): Response
    {
        return $this->render('contacto/modal/contacto_show.html.twig', [
            'contacto' => $contacto,
        ]);
    }

    /**
     * Reporte de usuarios
     */
    public function reporteDashboard(): Response
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('contacto/_dashboard.html.twig', [
            'contactos' => $em->getRepository(Contacto::class)->findReporteTotal(),
            'perfiles' => $em->getRepository(Perfil::class)->findReporteTotal(),
            'ubicaciones' => $em->getRepository(Ubicacion::class)->findReporteTotal(),
        ]);
    }
}

