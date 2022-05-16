<?php

namespace App\Controller\System;

use App\Entity\System\Job;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class JobCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Job::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
                    ->setPageTitle('index', function (){
                        return '<i class="menu-icon fa-fw fas fa-user-friends"></i> Plazas';
                    })
                    ->setPageTitle('new', function (){
                        return '<i class="menu-icon fa-fw fas fa-user-friends"></i> Registrar';
                    })
                    ->setPageTitle('edit', function (){
                        return '<i class="menu-icon fa-fw fas fa-user-friends"></i> Modificar';
                    })
                    ->setEntityPermission('ROLE_ADMIN')
                    ->showEntityActionsInlined()
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('job')
                ->setLabel('Plaza')
                ->setColumns('12'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
                    ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                        return $action
                                    ->setIcon('fa fa-plus')
                                    ->setLabel('Registrar');
                    })
        ;
    }
}
