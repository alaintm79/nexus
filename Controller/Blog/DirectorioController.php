<?php

namespace App\Controller\Blog;

use App\Repository\Sistema\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  Intranet Modulos controller.
 *
 *  @Route("blog/directorio")
 */
class DirectorioController extends AbstractController
{
    /**
     * @Route("/correos/", name="app_blog_directorio_correo")
     */
    public function correo(UsuarioRepository $usuarios): Response
    {
        return $this->render('blog/directorio.html.twig', [
            'usuarios' => $this->isGranted('IS_AUTHENTICATED_FULLY') ? $usuarios->findAllUsuariosWithCorreo() : [],
            'directorio' => 'Correos'
        ]);
    }
}
