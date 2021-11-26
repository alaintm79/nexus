<?php

namespace App\Controller\Contacto;

use App\Entity\Contacto\Contacto;
use App\Entity\Contacto\Perfil;
use App\Entity\Contacto\Ubicacion;
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

        return $this->render('contacto/include/_dashboard.html.twig', [
            'contactos' => $em->getRepository(Contacto::class)->findReporteTotal(),
            'perfiles' => $em->getRepository(Perfil::class)->findReporteTotal(),
            'ubicaciones' => $em->getRepository(Ubicacion::class)->findReporteTotal(),
        ]);
    }
}

