<?php

namespace App\Controller\IT;

use App\Entity\IT\Brand;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BrandCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Brand::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
                    ->setPageTitle('index', function (){
                        return '<i class="menu-icon fa-fw fa fas fa-list"></i> Nomencladores / Marcas';
                    })
                    ->setPageTitle('new', function (){
                        return '<i class="menu-icon fa-fw fa fas fa-list"></i> Registrar';
                    })
                    ->setPageTitle('edit', function (){
                        return '<i class="menu-icon fa-fw fa fas fa-list"></i> Modificar';
                    })
                    ->setEntityPermission('ROLE_ADMIN')
                    ->showEntityActionsInlined()
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('brand')
                ->setLabel('Marca')
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
