<?php

namespace App\Controller\Blog\Admin;

use App\Service\Cache;
use App\Service\Notify;
use App\Entity\Blog\Estado;
use App\Entity\Blog\Publicacion;
use App\Form\Blog\Admin\PublicacionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\Blog\PublicacionRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *  Publicacion controller.
 *  @Route("blog/admin/publicaciones")
 */
class PublicacionController extends AbstractController
{
    private const EMAIL_TEMPLATE = 'blog/admin/notify.html.twig';
    private const CACHE_LATEST_ID = 'app_post_latest_cache';
    private const CACHE_RECOMMENDED_ID = 'app_post_recommended_cache';

    /**
     * @Route("/estado/{estado}",
     *      name="app_blog_admin_publicacion_index",
     *      requirements={"estado": "publicado|borrador|eliminado|programado"},
     *      methods={"GET"}
     * )
     */
    public function index(PublicacionRepository $publicaciones, string $estado): Response
    {
        $titulo = [
            'publicado' => 'Publicaciones',
            'borrador' => 'Borradores',
            'eliminado' => 'Eliminadas',
            'programado' => 'Programados'
        ];

        $breadcrumb = [
            ['title' => 'Publicaciones']
        ];

        return $this->render('blog/admin/publicacion.html.twig',[
            'total' => $publicaciones->findTotalesByEstado(),
            'estado' => $estado,
            'titulo' => $titulo[$estado],
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/{estado}/list",
     *      name="app_blog_admin_publicacion_list",
     *      requirements={"estado": "publicado|borrador|eliminado|programado"},
     *      methods={"GET"}
     * )
     */
    public function list(PublicacionRepository $publicaciones, string $estado): Response
    {
        return new JsonResponse($publicaciones->findPublicacionesByEstado($estado));
    }

    /**
     * @Route("/new",
     *      name="app_blog_admin_publicacion_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request, Notify $notify, Cache $cache): Response
    {
        $publicacion = new Publicacion();
        $form = $this->createForm(PublicacionType::class, $publicacion);
        $breadcrumb = [
            ['title' => 'Publicaciones', 'url' => $this->generateUrl('app_blog_admin_publicacion_index', ['estado' => 'publicado'])],
            ['title' => 'Registrar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if($request->request->get('datetime_status') === 'default'){
                $publicacion->setFechaPublicacion(new \DateTime());
            }

            $publicacion->setAutor($this->getUser());

            $em->persist($publicacion);
            $em->flush();

            $cache->deleteMultiple([self::CACHE_LATEST_ID, self::CACHE_RECOMMENDED_ID]);

            $this->addFlash('notice', 'Publicación registrada con exito!');

            if ('publicado' === $publicacion->getEstado()->getEstado() && !$publicacion->getIsSent()) {
                $notify->send($this->getParameter('app_notify_blog'), $publicacion, self::EMAIL_TEMPLATE);
                $publicacion->setIsSent(\true);

                $em->persist($publicacion);
                $em->flush();
            }

            return $this->redirectToRoute('app_blog_admin_publicacion_edit', [
                'id' => $publicacion->getId()
            ]);
        }

        return $this->render('blog/admin/form/publicacion_form.html.twig', [
            'form' => $form->createView(),
            'publicacion' => $publicacion,
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/edit",
     *      name="app_blog_admin_publicacion_edit",
     *      methods={"GET", "POST"}
     * )
     */
    public function edit(Request $request, Publicacion $publicacion, Notify $notify, Cache $cache): Response
    {
        $options = ['estado' => $publicacion->getEstado()];
        $form = $this->createForm(PublicacionType::class, $publicacion, $options);
        $breadcrumb = [
            [
                'title' => 'Publicaciones',
                'url' => $this->generateUrl('app_blog_admin_publicacion_index', ['estado' => 'publicado'])
            ],
            ['title' => 'Modificar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($publicacion);
            $em->flush();

            $cache->deleteMultiple([self::CACHE_LATEST_ID, self::CACHE_RECOMMENDED_ID]);

            $this->addFlash('notice', 'Publicación modificada con exito!');

            if ('publicado' === $publicacion->getEstado()->getEstado() && !$publicacion->getIsSent()) {
                $notify->send($this->getParameter('app_notify_blog'), $publicacion, self::EMAIL_TEMPLATE);
                $publicacion->setIsSent(\true);

                $em->persist($publicacion);
                $em->flush();
            }
        }

        return $this->render('blog/admin/form/publicacion_form.html.twig', [
            'breadcrumb' => $breadcrumb,
            'estado' => $publicacion->getEstado(),
            'form' => $form->createView(),
            'publicacion' => $publicacion,
        ]);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/delete",
     *      name="app_blog_admin_publicacion_delete",
     *      methods={"GET"}
     * )
     */
    public function delete(Publicacion $publicacion, Cache $cache): Response
    {
        $em = $this->getDoctrine()->getManager();
        $redirectTo = \strtolower($publicacion->getEstado()->getEstado());

        $publicacion->setIsDelete(\true);
        $em->flush();

        $cache->deleteMultiple([self::CACHE_LATEST_ID, self::CACHE_RECOMMENDED_ID]);

        $this->addFlash('notice', 'Publicación borrada con exito!');

        return $this->redirectToRoute('app_blog_admin_publicacion_index', [
            'estado' => $redirectTo
        ]);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/remove",
     *      name="app_blog_admin_publicacion_remove",
     *      methods={"GET"}
     * )
     */
    public function remove(Publicacion $publicacion, Cache $cache): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($publicacion);
        $em->flush();

        $cache->deleteMultiple([self::CACHE_LATEST_ID, self::CACHE_RECOMMENDED_ID]);

        $this->addFlash('notice', 'Publicación eliminada con exito!');

        return $this->redirectToRoute('app_blog_admin_publicacion_index', [
            'estado' => 'eliminado'
        ]);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/restore",
     *      name="app_blog_admin_publicacion_restore",
     *      methods={"GET"}
     * )
     */
    public function restore(Publicacion $publicacion, Cache $cache): Response
    {
        $em = $this->getDoctrine()->getManager();

        $publicacion->setIsDelete(false);
        $em->flush();

        $cache->deleteMultiple([self::CACHE_LATEST_ID, self::CACHE_RECOMMENDED_ID]);

        $this->addFlash('notice', 'Publicación restaurada con exito!');

        return $this->redirectToRoute('app_blog_admin_publicacion_index', [
            'estado' => 'eliminado'
        ]);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/notify",
     *      name="app_blog_admin_publicacion_notify",
     *      methods={"GET"}
     * )
     */
    public function sendNotify(Publicacion $publicacion, Notify $notify): Response
    {
        if ('publicado' === $publicacion->getEstado()->getEstado() && $publicacion->getIsSent()) {
            $notify->send($this->getParameter('app_notify_blog'), $publicacion, self::EMAIL_TEMPLATE);
        }

        $this->addFlash('notice', 'Notificacion de publicación reenviada con exito!');

        return $this->redirectToRoute('app_blog_admin_publicacion_index', [
            'estado' => $publicacion->getEstado()->getEstado()
        ]);
    }

    /**
     * @Route("/batch",
     *      name="app_blog_admin_publicacion_batch",
     *      methods={"POST"}
     * )
     */
    public function batch(Request $request, EntityManagerInterface $em, Cache $cache): Response
    {
        $whitelist = [
            '/blog/admin/publicaciones/estado/publicado',
            '/blog/admin/publicaciones/estado/borrador',
            '/blog/admin/publicaciones/estado/eliminado',
            '/blog/admin/publicaciones/estado/programado',
        ];

        $redirectTo = $request->request->get('redirect_to');

        if(!\in_array($redirectTo, $whitelist)){
            throw new \InvalidArgumentException('Error de url de retorno');
        }

        if (!$this->isCsrfTokenValid('bulk-action', $request->request->get('token'))
            || !$request->request->has('id')
        ) {
            $this->addFlash('error', 'Imposible ejecutar la acción, datos no validos o nulos');

            return new RedirectResponse($redirectTo);
        }

        $data = $request->request->all();
        $ids = implode(', ', $data['id']);
        $action = $data['action'];
        $batchSize = 20;
        $i = 1;

        $dql = ($action !== 'eliminar' ? 'SELECT p' : 'DELETE') . ' FROM App\Entity\Blog\Publicacion p WHERE p.id IN ('.$ids.')';
        $q = $em->createQuery($dql);

        if($action !== 'eliminar'){
            foreach ($q->toIterable() as $row) {
                if($action === 'borrar'){
                    $row->setIsDelete(\true);
                }elseif($action === 'borrador'){
                    $row->setEstado($em->getReference('App:Blog\Estado', 1));
                }elseif($action === 'publicado'){
                    $row->setEstado($em->getReference('App:Blog\Estado', 2));
                }elseif($action === 'restaurar'){
                    $row->setIsDelete(\false);
                }elseif($action === 'es_relevante'){
                    $row->setIsSticky(\true);
                }elseif($action === 'no_relevante'){
                    $row->setIsSticky(\false);
                }

                ++$i;

                if (($i % $batchSize) === 0) {
                    $em->flush(); // Executes all updates.
                    $em->clear(); // Detaches all objects from Doctrine!
                }
            }

            $em->flush();
            $em->clear();

        }elseif($action === 'eliminar'){
            $q->execute();
        }

        $cache->deleteMultiple([self::CACHE_LATEST_ID, self::CACHE_RECOMMENDED_ID]);

        $this->addFlash('notice', 'Acción en lotes ejecutada con exito!');

        return new RedirectResponse($redirectTo);
    }
}
