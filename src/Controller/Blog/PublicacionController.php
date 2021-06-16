<?php

namespace App\Controller\Blog;

use App\Service\Cache;
use App\Service\Notify;
use App\Entity\Blog\Estado;
use App\Entity\Blog\Publicacion;
use App\Form\Blog\PublicacionType;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\Sistema\UsuarioRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\Blog\PublicacionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *  Publicacion controller.
 *  @Route("blog/publicaciones")
 */
class PublicacionController extends AbstractController
{
    private const CACHE_LATEST_ID = 'app_post_latest_cache';
    private const CACHE_RECOMMENDED_ID = 'app_post_recommended_cache';

    /**
     * @Route("/",
     *      name="app_blog_publicacion",
     *      methods={"GET"}
     * )
     */
    public function index(PublicacionRepository $publicaciones, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $publicaciones->findAllPublicacionesWithQueryBuilder('Publicado');
        $limit = $this->getParameter('app_pagination_post');

        $pages = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $limit /*limit per page*/
        );

        // parameters to template
        return $this->render('blog/page.html.twig', ['pages' => $pages]);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/{slug}",
     *      name="app_blog_publicacion_show",
     *      methods={"GET"}
     * )
     */
    public function show(PublicacionRepository $publicacion, int $id, string $slug): Response
    {
        return $this->render('blog/show.html.twig',[
            'publicacion' => $publicacion->findPublicacionByIdAndSlug($id, $slug)
        ]);
    }

    public function latestPosts(PublicacionRepository $publicaciones, Cache $cache, string $route): Response
    {
        $max = $this->getParameter('app_lastest_post');

        if(null === $cache->get(self::CACHE_LATEST_ID)){
            $cache->set(self::CACHE_LATEST_ID, $publicaciones->findLatestOrRecommended($max));
        }

        return $this->render('blog/_latest_posts.html.twig',[
            'publicaciones' => $cache->get(self::CACHE_LATEST_ID),
            'route' => $route
        ]);
    }

    public function recommendedPosts(PublicacionRepository $publicaciones, Cache $cache): Response
    {
        $max = $this->getParameter('app_recommended_post');

        if(null === $cache->get(self::CACHE_RECOMMENDED_ID)){
            $cache->set(self::CACHE_RECOMMENDED_ID, $publicaciones->findLatestOrRecommended($max, true));
        }

        return $this->render('blog/_recommended_posts.html.twig',[
            'publicaciones' => $cache->get(self::CACHE_RECOMMENDED_ID, $cache->get(self::CACHE_LATEST_ID))
        ]);
    }
}
