<?php

namespace App\Controller\Blog\Admin;

use App\Entity\Blog\Modulo;
use App\Form\Blog\ModuloType;
use App\Repository\Blog\ModuloRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  Intranet Modulos controller.
 *  @Route("blog/admin/modulos")
 */
class ModuloController extends AbstractController
{
    /**
     * @Route("/", name="app_blog_admin_modulo_index")
     */
    public function index(): Response
    {
        return $this->render('blog/admin/modulo.html.twig');
    }

    /**
     * @Route("/list",
     *      name="app_blog_admin_modulo_list",
     *      methods={"GET"}
     * )
     */
    public function read (ModuloRepository $modulos): Response
    {
        return new JsonResponse($modulos->findAll());
    }

    /**
     * Displays a form to edit an existing modulo entity.
     *
     * @Route("/{id}/edit",
     *      name="app_blog_admin_modulo_edit",
     *      methods={"GET", "POST"})
     */
    public function edit(Request $request, Modulo $modulo): Response
    {
        $form = $this->createForm(ModuloType::class, $modulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'Módulo modificado con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('blog/admin/modal/modulo_form.html.twig', [
            'form' => $form->createView(),
            'modulo' => $modulo,
        ]);
    }
}
