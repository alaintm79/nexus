<?php

namespace App\Controller\Blog\Admin;

use App\Entity\Blog\Comentario;
use App\Entity\Blog\Publicacion;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     * Reporte de usuarios
     */
    public function reporteDashboard(): Response
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('blog/admin/include/_dashboard.html.twig', [
            'publicaciones' => $em->getRepository(Publicacion::class)->findTotalesByEstado(),
            'comentarios' => $em->getRepository(Comentario::class)->findTotalesByEstado(),
        ]);
    }
}
