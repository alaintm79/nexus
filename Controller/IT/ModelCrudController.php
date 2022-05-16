<?php

namespace App\Controller\IT;

use App\Entity\IT\Model;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ModelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Model::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
                    ->setPageTitle('index', function (){
                        return '<i class="menu-icon fa-fw fa fas fa-list"></i> Nomencladores / Modelos';
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
            AssociationField::new('brand')
                ->setLabel('Marca')
                ->setRequired(true)
                ->setFormTypeOptions([
                    'placeholder' => ''
                ])
                ->setFormTypeOptions([
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('b')
                            ->orderBy('b.brand', 'ASC');
                    },
                    'placeholder' => '',
                ])
                ->setColumns('col-12 col-lg-4'),
            TextField::new('model')
                ->setLabel('Modelo')
                ->setColumns('col-12 col-lg-4'),
            AssociationField::new('device')
                ->setLabel('Tipo')
                ->setFormTypeOptions([
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('d')
                            ->orderBy('d.device', 'ASC');
                    },
                    'placeholder' => '',
                ])
                ->setColumns('col-12 col-lg-4'),
            TextField::new('component')
                ->setLabel('Consumibles')
                ->setColumns('col-12 col-lg-8'),
            ImageField::new('image')
                ->setLabel('Imagen')
                ->setBasePath('uploads/it/devices')
                ->setUploadDir('public/uploads/it/devices')
                ->setUploadedFileNamePattern('device-[contenthash].[extension]')
                ->setColumns('col-12  col-lg-4'),
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
                    ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }
}
