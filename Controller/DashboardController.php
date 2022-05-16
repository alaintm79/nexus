<?php

namespace App\Controller;

use App\Controller\IT\LineCrudController;
use App\Controller\IT\MobileCrudController;
use App\Controller\IT\PrinterCrudController;
use App\Controller\System\UserCrudController;
use App\Entity\IT\Brand;
use App\Entity\IT\Device;
use App\Entity\IT\Line;
use App\Entity\IT\Mobile;
use App\Entity\IT\Model;
use App\Entity\IT\Printer;
use App\Entity\System\Area;
use App\Entity\System\Job;
use App\Entity\System\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            ->setTitle('<span class="fs-1 fw-bold">Nexus</span>')
        ;
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
                    ->setPaginatorPageSize(15)
        ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Inicio', 'fa fa-home');
        yield MenuItem::section('TIC');
        yield MenuItem::linkToCrud('Líneas', 'fa fas fa-sim-card', Line::class)->setController(LineCrudController::class);
        yield MenuItem::linkToCrud('Móviles', 'fa fas fa-mobile', Mobile::class)->setController(MobileCrudController::class);
        yield MenuItem::linkToCrud('Impresoras', 'fa fas fa-print', Printer::class)->setController(PrinterCrudController::class);
        yield MenuItem::subMenu('Nomencladores', 'fa fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Marcas', 'far fa-circle', Brand::class),
            MenuItem::linkToCrud('Modelos', 'far fa-circle', Model::class),
            MenuItem::linkToCrud('Dispositivos', 'far fa-circle', Device::class),
        ])->setPermission('ROLE_ADMIN');
        yield MenuItem::section('Sistema');
        yield MenuItem::linkToCrud('Usuarios', 'fa fas fa-users', User::class)->setController(UserCrudController::class);
        yield MenuItem::linkToCrud('Plazas', 'fas fa-user-friends', Job::class)
                            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Areas', 'fas fa-house-user', Area::class)
                            ->setPermission('ROLE_ADMIN');
        yield MenuItem::section('');
        yield MenuItem::linkToLogout('Salir', 'fa fa-fw fa-sign-out');
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
                    ->addWebpackEncoreEntry('app')
        ;
    }
}
