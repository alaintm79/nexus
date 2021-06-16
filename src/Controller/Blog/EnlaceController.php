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
 *
 *  @Route("blog/enlaces")
 */
class EnlaceController extends AbstractController
{
    private const CACHE_ID = 'app_menu_enlace_cache';

    /**
     * @Route("/{ruta}", name="app_blog_enlace_index")
     */
    public function index(string $ruta): Response
    {
        return $this->render('blog/home.html.twig');
    }

    public function menu(Cache $cache, EnlaceRepository $enlaces, string $route): Response
    {
        if(null === $cache->get(self::CACHE_ID)){
            $cache->set(self::CACHE_ID, $enlaces->findByIsMenu());
        }

        return $this->render('blog/_menu_enlace.html.twig', [
            'menu' => $cache->get(self::CACHE_ID),
            'route' => $route,
        ]);
    }
}
