<?php

namespace App\Controller\Blog\Admin;

use App\Entity\Blog\Categoria;
use App\Form\Blog\CategoriaType;
use App\Repository\Blog\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  Categorias Modulos controller.
 *
 *  @Route("blog/admin/categorias")
 */
class CategoriaController extends AbstractController
{
    /**
     * @Route("/", name="app_blog_admin_categoria_index")
     */
    public function index(): Response
    {
        return $this->render('blog/admin/categoria.html.twig');
    }

    /**
     * @Route("/list",
     *      name="app_blog_admin_categoria_list",
     *      methods={"GET"}
     * )
     */
    public function read (CategoriaRepository $categorias): Response
    {
        return new JsonResponse($categorias->findAll());
    }

    /**
     * Creates a new archivo entity.
     *
     * @Route("/new",
     *      name="app_blog_admin_categoria_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request): Response
    {
        $categoria = new Categoria();
        $form = $this->createForm(CategoriaType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($categoria);
            $em->flush();

            $this->addFlash('notice', 'Categoria registrada con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('blog/admin/modal/categoria_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing modulo entity.
     *
     * @Route("/{id}/edit",
     *      name="app_blog_admin_categoria_edit",
     *      methods={"GET", "POST"})
     */
    public function edit(Request $request, Categoria $categoria): Response
    {
        $form = $this->createForm(CategoriaType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'Categoria modificada con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('blog/admin/modal/categoria_form.html.twig', [
            'form' => $form->createView(),
            'categoria' => $categoria,
        ]);
    }
}
