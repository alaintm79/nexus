<?php

namespace App\Controller\Blog\Admin;

use App\Service\Cache;
use App\Entity\Blog\Enlace;
use App\Form\Blog\EnlaceType;
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
        return $this->render('blog/admin/enlace.html.twig');
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($enlace);
            $em->flush();

            $cache->delete(self::CACHE_ID);

            $this->addFlash('notice', 'Enlace registrado con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('blog/admin/modal/enlace_form.html.twig', [
            'form' => $form->createView()
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $cache->delete(self::CACHE_ID);

            $this->addFlash('notice', 'Enlace modificado con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('blog/admin/modal/enlace_form.html.twig', [
            'form' => $form->createView(),
            'enlace' => $enlace
        ]);
    }

    /**
     * Deletes a enlace entity.
     *
     * @Route("/{id}/delete",
     *      name="app_blog_admin_enlace_delete",
     *      methods={"GET", "POST"}
     * )
     */
    public function delete(Request $request, Enlace $enlace, Cache $cache): Response
    {
        if($request->isMethod('POST')){

            if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
                $this->addFlash('error', 'Imposible eliminar enlace');

                return $this->render('common/notify.html.twig', []);
            }

            $em = $this->getDoctrine()->getManager();
            $em->remove($enlace);
            $em->flush();

            $cache->delete(self::CACHE_ID);

            $this->addFlash('notice', 'Enlace eliminado con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('blog/admin/modal/enlace_delete.html.twig', [
            'enlace' => $enlace,
        ]);
    }
}
