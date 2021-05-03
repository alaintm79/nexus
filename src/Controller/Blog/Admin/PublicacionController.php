<?php

namespace App\Controller\Blog\Admin;

use App\Entity\Blog\Estado;
use App\Entity\Blog\Publicacion;
use App\Form\Blog\PublicacionType;
use App\Repository\Blog\PublicacionRepository;
use App\Repository\Sistema\UsuarioRepository;
use App\Service\Notify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  Publicacion controller.
 *  @Route("blog/admin/publicaciones")
 */
class PublicacionController extends AbstractController
{
    private const EMAIL_TEMPLATE = 'blog/admin/notify.html.twig';

    /**
     * @Route("/estado/{estado}",
     *      name="app_blog_admin_publicacion_index",
     *      requirements={"estado": "publicado|borrador|eliminado"},
     *      methods={"GET"}
     * )
     */
    public function index(PublicacionRepository $publicaciones, string $estado): Response
    {
        $titulo = [
            'publicado' => 'Publicados',
            'borrador' => 'Borradores',
            'eliminado' => 'Eliminadas'
        ];

        return $this->render('blog/admin/publicacion.html.twig',[
            'total' => $publicaciones->findTotalesByEstado(),
            'estado' => $estado,
            'titulo' => $titulo[$estado]
        ]);
    }

    /**
     * @Route("/{estado}/list",
     *      name="app_blog_admin_publicacion_list",
     *      requirements={"estado": "publicado|borrador|eliminado"},
     *      methods={"GET"}
     * )
     */
    public function list(PublicacionRepository $publicaciones, string $estado): Response
    {
        return new JsonResponse($publicaciones->findPublicacionesByEstado(\ucfirst($estado)));
    }

    /**
     * @Route("/new",
     *      name="app_blog_admin_publicacion_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request, Notify $notify )
    {
        $publicacion = new Publicacion();
        $form = $this->createForm(PublicacionType::class, $publicacion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $publicacion->setAutor($this->getUser());

            $em->persist($publicacion);
            $em->flush();

            $this->addFlash('notice', 'Publicación registrada con exito!');

            if('Publicado' === $publicacion->getEstado()->getEstado() && $request->request->has('btn_save_send') ){
                $notify->send($this->getParameter('app_notify_blog'), $publicacion, self::EMAIL_TEMPLATE);
            }

            return $this->redirectToRoute('app_blog_admin_publicacion_edit', [
                'id' => $publicacion->getId()
            ]);
        }

        return $this->render('blog/admin/modal/publicacion_form.html.twig', [
            'form' => $form->createView(),
            'publicacion' => $publicacion
        ]);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/edit",
     *      name="app_blog_admin_publicacion_edit",
     *      methods={"GET", "POST"}
     * )
     */
    public function edit(Request $request, Publicacion $publicacion, Notify $notify)
     {
        $options = ['estado' => $publicacion->getEstado()];
        $form = $this->createForm(PublicacionType::class, $publicacion, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($publicacion);
            $em->flush();

            $this->addFlash('notice', 'Publicación modificada con exito!');

            if($request->request->has('restore')){
                return $this->redirectToRoute('app_blog_admin_publicacion_index', [
                    'estado' => \strtolower($publicacion->getEstado())
                ]);
            }

            if('Publicado' === $publicacion->getEstado()->getEstado() && $request->request->has('btn_save_send') ){
                $notify->send($this->getParameter('app_notify_blog'), $publicacion, self::EMAIL_TEMPLATE);
            }

            return $this->redirectToRoute('app_blog_admin_publicacion_edit', [
                'id' => $publicacion->getId(),
            ]);
        }

            return $this->render('blog/admin/modal/publicacion_form.html.twig', [
            'form' => $form->createView(),
            'publicacion' => $publicacion
        ]);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/trash",
     *      name="app_blog_admin_publicacion_trash",
     *      methods={"GET"}
     * )
     */
    public function trash(Request $request, Publicacion $publicacion): Response
    {
        $em = $this->getDoctrine()->getManager();
        $estado = $em->getRepository(Estado::class)->findOneBy(['estado' => 'Eliminado']);
        $url = explode('/', $request->headers->get('referer'));

        $publicacion->setEstado($estado);
        $em->flush();

        $this->addFlash('notice', 'Publicación borrada con exito!');

        if(\end($url) === 'edit'){
            return $this->redirectToRoute('app_blog_admin_publicacion_index', [
                'estado' => 'publicado'
            ]);
        }

        return $this->render('common/notify.html.twig', []);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/delete",
     *      name="app_blog_admin_publicacion_delete",
     *      methods={"GET", "POST"}
     * )
     */
    public function delete(Request $request, Publicacion $publicacion): Response
    {
        if($request->isMethod('POST')){

            if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
                $this->addFlash('error', 'Imposible eliminar publicación');

                return $this->render('common/notify.html.twig', []);
            }

            $em = $this->getDoctrine()->getManager();
            $em->remove($publicacion);
            $em->flush();

            $this->addFlash('notice', 'Publicación eliminada con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('blog/admin/modal/publicacion_delete.html.twig', [
            'publicacion' => $publicacion,
        ]);

    }
}
