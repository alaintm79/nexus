<?php

namespace App\Controller;

use App\Entity\System\User;
use App\Controller\System\UserCrudController;
use App\Controller\System\UserDeletedCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Nexus');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Sistema');
        yield MenuItem::subMenu('Usuarios', 'fa fas fa-users')->setSubItems([
            MenuItem::linkToCrud('Activos', 'far fa-circle', User::class)->setController(UserCrudController::class),
            MenuItem::linkToCrud('Bajas', 'far fa-circle', User::class)->setController(UserDeletedCrudController::class),
        ]);
        yield MenuItem::linkToLogout('Salir', 'fa fa-fw fa-sign-out');
    }
}
