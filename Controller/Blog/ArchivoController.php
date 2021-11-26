<?php

namespace App\Controller\Blog;

use App\Service\Cache;
use Symfony\Component\Finder\Finder;
use App\Repository\Blog\ArchivoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *  Intranet Modulos controller.
 *
 *
 *  @Route("blog/archivos")
 */
class ArchivoController extends AbstractController
{
    private const CACHE_ID = 'app_menu_archivo_cache';

    /**
     * @Route("/{ruta}", name="app_blog_archivo_index")
     */
    public function index(ArchivoRepository $archivo, string $ruta): Response
    {
        $archivo = $archivo->findByRuta($ruta);

        if(!$archivo){
            throw $this->createNotFoundException('El archivo no existe!');
        }

        $finder = new Finder();
        $finder->in(__DIR__.'/../../../public/uploads/files/'.$ruta);

        return $this->render('blog/archivo.html.twig', [
            'archivo' => $archivo,
            'assets' => $finder
        ]);
    }

    public function menu(Cache $cache, ArchivoRepository $archivo, string $route, array $params): Response
    {
        if(empty($cache->get(self::CACHE_ID)))
        {
            $cache->set(self::CACHE_ID, $archivo->findByIsActive());
        }

        return $this->render('blog/include/_menu_archivo.html.twig', [
            'menu' => \json_decode($cache->get(self::CACHE_ID)),
            'route' => $route,
            'params' => $params,
        ]);
    }
}
