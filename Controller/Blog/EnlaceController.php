<?php

namespace App\Controller\Blog;

use App\Service\Cache;
use App\Repository\Blog\EnlaceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *  Intranet Modulos controller.
 *
 *  @Route("blog/enlaces")
 */
class EnlaceController extends AbstractController
{
    private const CACHE_ID = 'app_menu_enlace_cache';

    /**
     * @Route("/", name="app_blog_enlace_index")
     */
    public function index(EnlaceRepository $enlaces): Response
    {
        $response = $this->render('blog/enlace.html.twig', [
            'enlaces' => $enlaces->findByIsActive()
        ]);

        $response->setPublic();
        $response->setMaxAge(300);

        // (optional) set a custom Cache-Control directive
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    public function menu(Cache $cache, EnlaceRepository $enlaces, string $route): Response
    {
        if(empty($cache->get(self::CACHE_ID))){
            $cache->set(self::CACHE_ID, $enlaces->findByIsMenu());
        }

        return $this->render('blog/include/_menu_enlace.html.twig', [
            'menu' => \json_decode($cache->get(self::CACHE_ID)),
            'route' => $route,
        ]);
    }
}
