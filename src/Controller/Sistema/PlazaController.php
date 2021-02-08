<?php

namespace App\Controller\Sistema;

use App\Entity\Sistema\Plaza;
use App\Form\Sistema\PlazaType;
use App\Repository\Sistema\PlazaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Plaza controller.
 *
 * @Route("sistema/plazas")
 */
class PlazaController extends AbstractController
{
    /**
     * @Route("/", name="app_plaza_index")
     */
    public function index(): Response
    {
        return $this->render('sistema/plaza/index.html.twig', []);
    }

    /**
     * @Route("/list",
     *      name="app_plaza_list",
     *      methods={"GET"}
     * )
     */
    public function list(PlazaRepository $plaza): Response
    {
        return new JsonResponse($plaza->findAll());
    }

    /**
     * Creates a new plaza entity.
     *
     * @Route("/new",
     *      name="app_plaza_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request): Response
    {
        $plaza = new Plaza();
        $form = $this->createForm(PlazaType::class, $plaza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($plaza);
            $em->flush();

            $this->addFlash('notice', 'Plaza registrada con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('sistema/plaza/modal/plaza_form.html.twig', [
            'form' => $form->createView(),
            'action' => 'create'
        ]);
    }

    /**
     * Displays a form to edit an existing plaza entity.
     *
     * @Route("/{id}/edit",
     *      name="app_plaza_edit",
     *      methods={"GET", "POST"})
     */
    public function edit(Request $request, Plaza $plaza): Response
    {
        $form = $this->createForm(PlazaType::class, $plaza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'Plaza modificada con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('sistema/plaza/modal/plaza_form.html.twig', [
            'form' => $form->createView(),
            'action' => 'edit'
        ]);
    }
}
