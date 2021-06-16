<?php

namespace App\Controller\Blog;

use App\Service\Cache;
use Symfony\Component\Finder\Finder;
use App\Repository\Blog\DirectorioRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *  Intranet Modulos controller.
 *
 *
 *  @Route("blog/directorios")
 */
class DirectorioController extends AbstractController
{
    private const CACHE_ID = 'app_menu_directorio_cache';

    /**
     * @Route("/{ruta}", name="app_blog_directorio_index")
     */
    public function index(DirectorioRepository $directorio, string $ruta): Response
    {
        $directorio = $directorio->findByRuta($ruta);

        if(!$directorio){
            throw $this->createNotFoundException('El directorio no existe!');
        }

        $finder = new Finder();
        $finder->in(__DIR__.'/../../../public/assets/files/'.$ruta);

        return $this->render('blog/directorio.html.twig', [
            'directorio' => $directorio,
            'archivos' => $finder
        ]);
    }

    public function menu(Cache $cache, DirectorioRepository $directorio, string $route, array $params): Response
    {
        if(null === $cache->get(self::CACHE_ID)){
            $cache->set(self::CACHE_ID, $directorio->findBy(
                ['isActive' => true ],
            ));
        }

        return $this->render('blog/_menu_directorio.html.twig', [
            'menu' => $cache->get(self::CACHE_ID),
            'route' => $route,
            'params' => $params,
        ]);
    }
}
