<?php

namespace App\Controller\Blog\Admin;

use App\Service\Cache;
use App\Entity\Blog\Enlace;
use App\Form\Blog\Admin\EnlaceType;
use App\Repository\Blog\EnlaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *  Blog Enlace controller
 *
 *  @Route("blog/admin/enlaces")
 */
class EnlaceController extends AbstractController
{
    private const CACHE_ID = 'app_menu_enlace_cache';

    /**
     * @Route("/", name="app_blog_admin_enlace_index")
     */
    public function index(): Response
    {
        $breadcrumb = [
            ['title' => 'Enlaces']
        ];

        return $this->render('blog/admin/enlace.html.twig', [
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/list",
     *      name="app_blog_admin_enlace_list",
     *      methods={"GET"}
     * )
     */
    public function list(EnlaceRepository $enlaces): Response
    {
        return new JsonResponse($enlaces->findAll());
    }

    /**
     * Creates a new archivo entity.
     *
     * @Route("/new",
     *      name="app_blog_admin_enlace_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request, Cache $cache): Response
    {
        $enlace = new Enlace();
        $form = $this->createForm(EnlaceType::class, $enlace);
        $breadcrumb = [
            ['title' => 'Enlaces', 'url' => $this->generateUrl('app_blog_admin_enlace_index')],
            ['title' => 'Registrar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($enlace);
            $em->flush();

            $cache->delete(self::CACHE_ID);

            $this->addFlash('notice', 'Enlace registrado con exito!');

            if ($form->get('saveAndReturn')->isClicked()) {
                return $this->redirectToRoute('app_blog_admin_enlace_new');
            }

            return $this->redirectToRoute('app_blog_admin_enlace_index');
        }

        return $this->render('blog/admin/form/enlace_form.html.twig', [
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Displays a form to edit an existing modulo entity.
     *
     * @Route("/{id}/edit",
     *      name="app_blog_admin_enlace_edit",
     *      methods={"GET", "POST"})
     */
    public function edit(Request $request, Enlace $enlace, Cache $cache): Response
    {
        $form = $this->createForm(EnlaceType::class, $enlace);
        $breadcrumb = [
            ['title' => 'Enlaces', 'url' => $this->generateUrl('app_blog_admin_enlace_index')],
            ['title' => 'Registrar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $cache->delete(self::CACHE_ID);

            $this->addFlash('notice', 'Enlace modificado con exito!');

            return $this->redirectToRoute('app_blog_admin_enlace_index');
        }

        return $this->render('blog/admin/form/enlace_form.html.twig', [
            'form' => $form->createView(),
            'enlace' => $enlace,
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Deletes a enlace entity.
     *
     * @Route("/{id}/delete",
     *      name="app_blog_admin_enlace_delete",
     *      methods={"GET"}
     * )
     */
    public function delete(Request $request, Enlace $enlace, Cache $cache): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($enlace);
        $em->flush();

        $cache->delete(self::CACHE_ID);

        $this->addFlash('notice', 'Enlace eliminado con exito!');

        return $this->redirectToRoute('app_blog_admin_enlace_index');
    }
}
