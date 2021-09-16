<?php

namespace App\Controller\Sistema;

use App\Repository\Sistema\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    /**
     * Reporte de usuarios
     */
    public function reporteDashboard(Request $request, UsuarioRepository $usuarios): Response
    {
        $unidad = $this->isGranted('ROLE_NX_ADMIN') ? 'ALL' : $request->getSession()->get('_unidad');

        return $this->render('sistema/include/_dashboard.html.twig', [
            'usuarios' => $usuarios->findReporteTotalUsuarios($unidad),
        ]);
    }
}
