<?php

namespace App\Controller\Blog;

use App\Entity\Blog\Comentario;
use App\Entity\Blog\Publicacion;
use App\Entity\Blog\PublicacionCounter;
use App\Form\Blog\ComentarioType;
use App\Repository\Blog\ComentarioRepository;
use App\Repository\Blog\PublicacionCounterRepository;
use App\Repository\Blog\PublicacionRepository;
use App\Service\Cache;
use App\Service\Notify;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  Publicacion controller.
 *  @Route("blog/publicaciones")
 */
class PublicacionController extends AbstractController
{
    private const CACHE_LATEST_ID = 'app_post_latest_cache';
    private const CACHE_RECOMMENDED_ID = 'app_post_recommended_cache';
    private const EMAIL_TEMPLATE = 'blog/notify.html.twig';

    /**
     * @Route("/",
     *      name="app_blog_publicacion",
     *      methods={"GET"}
     * )
     */
    public function index(PublicacionRepository $publicaciones, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $publicaciones->findAllPublicacionesWithQueryBuilder('publicado');
        $limit = $this->getParameter('app_pagination_post');

        $pages = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $limit /*limit per page*/
        );

        return $this->render('blog/page.html.twig', ['pages' => $pages]);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/{slug}",
     *      name="app_blog_publicacion_show",
     *      methods={"GET"}
     * )
     */
    public function show(Request $request, PublicacionRepository $publicacion, ComentarioRepository $comentarios, EntityManagerInterface $em, int $id, string $slug): Response
    {
        $preview = $request->query->has('preview') ? true : false;

        $response = $this->render('blog/show.html.twig',[
            'publicacion' => $publicacion->findPublicacionByIdAndSlug($id, $slug),
            'comentarios' => $comentarios->findComentariosByPublicacionId($id)
        ]);

        if(!$preview){
            // cache publicly for 300 seconds (5 mins)
            $response->setPublic();
            $response->setMaxAge(300);

            // (optional) set a custom Cache-Control directive
            $response->headers->addCacheControlDirective('must-revalidate', true);
        }

        return $response;
    }

    /**
     * @Route("/counter",
     *      name="app_blog_publicacion_counter",
     *      methods={"POST"}
     * )
     */
    public function counter(Request $request, EntityManagerInterface $em, PublicacionCounterRepository $publicacionCounter): Response
    {
            $data = \json_decode($request->getContent(), true);
            $exclude = $this->getParameter('app_exclude_ips');
            $response = [
                'message' => 'success',
            ];

            if(\in_array($request->getClientIp(), $exclude)){
                $response['total'] = $publicacionCounter->findPublicacionCounterById($data['id']);

                return new JsonResponse($response, 200);
            }

            if(!$request->query->has('is_reloaded')){
                $counter = new PublicacionCounter();

                $counter->setPublicacion($em->getReference('App:Blog\Publicacion', $data['id']));
                $counter->setIp($request->getClientIp());

                $em->persist($counter);
                $em->flush();

                $response['total'] = $publicacionCounter->findPublicacionCounterById($data['id']);
            }

            return new JsonResponse($response, 200);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/comentario/new",
     *      methods={"POST"},
     *      name="app_blog_comentario_new"
     * )
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function comentarioNew(Request $request, Publicacion $publicacion, Notify $notify): Response
    {
        $comentario = new Comentario();
        $comentario->setUsuario($this->getUser());
        $publicacion->addComentario($comentario);

        $form = $this->createForm(ComentarioType::class, $comentario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($comentario);
            $em->flush();

            $this->addFlash('blog_notice', 'Comentario registrado con exito!, ha sido enviado la notificación a nuestro webmaster para su aprobación');

            $notify->send(
                $this->getParameter('app_notify_webmaster'),
                $comentario,
                self::EMAIL_TEMPLATE,
                'Nuevo comentario en la publicación: '.$publicacion->getTitulo()
            );

            return $this->redirectToRoute('app_blog_publicacion_show', [
                'id' => $publicacion->getId(),
                'slug' => $publicacion->getSlug()
            ]);
        }

        return $this->render('blog/comentario_error.html.twig', [
            'publicacion' => $publicacion,
            'form' => $form->createView(),
        ]);
    }

    public function latestPosts(PublicacionRepository $publicaciones, Cache $cache, string $route): Response
    {
        $max = $this->getParameter('app_lastest_post');

        if(empty($cache->get(self::CACHE_LATEST_ID))){
            $cache->set(self::CACHE_LATEST_ID, $publicaciones->findLatestOrRecommended($max));
        }

        $response = $this->render('blog/include/_latest_posts.html.twig',[
            'publicaciones' => \json_decode($cache->get(self::CACHE_LATEST_ID)),
            'route' => $route
        ]);

        // cache publicly for 300 seconds (5 min)
        $response->setPublic();
        $response->setMaxAge(300);

        // (optional) set a custom Cache-Control directive
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    public function recommendedPosts(PublicacionRepository $publicaciones, Cache $cache): Response
    {
        $max = $this->getParameter('app_recommended_post');

        if(empty($cache->get(self::CACHE_RECOMMENDED_ID))){
            $cache->set(self::CACHE_RECOMMENDED_ID, $publicaciones->findLatestOrRecommended($max, true));
        }

        $response = $this->render('blog/include/_recommended_posts.html.twig',[
            'publicaciones' => \json_decode($cache->get(self::CACHE_RECOMMENDED_ID, $cache->get(self::CACHE_RECOMMENDED_ID)))
        ]);

        // cache publicly for 300 seconds (5 min)
        $response->setPublic();
        $response->setMaxAge(300);

        // (optional) set a custom Cache-Control directive
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    public function comentarioForm(Publicacion $publicacion): Response
    {
        $form = $this->createForm(ComentarioType::class);

        return $this->render('blog/include/_comentario_form.html.twig', [
            'publicacion' => $publicacion,
            'form' => $form->createView(),
        ]);
    }

    public function moreViewsPosts(PublicacionRepository $publicaciones): Response
    {
        $max = $this->getParameter('app_more_views_post');

        $response = $this->render('blog/include/_more_views.html.twig',[
            'publicaciones' => $publicaciones->findMoreViews($max)
        ]);

        // cache publicly for 60 seconds (1 min)
        $response->setPublic();
        $response->setMaxAge(60);

        // (optional) set a custom Cache-Control directive
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }
}
