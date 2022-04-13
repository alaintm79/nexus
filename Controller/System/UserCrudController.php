<?php

namespace App\Controller\System;

use App\Entity\System\User;
use Doctrine\ORM\QueryBuilder;
use App\Entity\System\UserInfo;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
                    ->andWhere('entity.isDeleted = :deleted')
                    ->setParameter('deleted', false);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
                    ->setPageTitle('index', function (){
                        return 'Usuarios activos';
                    })
                    ->setPageTitle('new', function (){
                        return 'Registrar';
                    })
                    ->setPageTitle('edit', function (){
                        return 'Modificar';
                    })
                    ->setPageTitle('detail', function (User $user){
                        return 'Usuario '.$user->getFirstName().' '.$user->getLastname();
                    })
                    ->setFormOptions(['error_mapping' => [
                            'ciValid' => 'idCard',
                            'accountValid' => 'username'
                    ]])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Datos generales'),
            TextField::new('firstname')
                ->setLabel('Nombre')
                ->setColumns('col-12 col-lg-4'),
            TextField::new('lastname')
                ->setLabel('Apellidos')
                ->setColumns('col-12 col-lg-4'),
            TextField::new('idCard')
                ->setLabel('Carnet')
                ->setColumns('col-12 col-lg-4')
                ->hideOnIndex(),
            AssociationField::new('unit')
                ->setLabel('Unidad')
                ->setColumns('col-12 col-lg-4'),
            AssociationField::new('job')
                ->setLabel('Plaza')
                ->setColumns('col-12 col-lg-4'),
            AssociationField::new('area')
                ->setLabel('Area')
                ->setColumns('col-12 col-lg-4'),
            FormField::addPanel('Cuenta de usuario'),
            TextField::new('username')
                ->setLabel('Usuario')
                ->setColumns('col-12 col-lg-4'),
            EmailField::new('email')
                ->setLabel('Correo')
                ->setColumns('col-12 col-lg-4')
                ->hideOnIndex(),
            ArrayField::new('role')
                ->setLabel('Roles')
                ->onlyOnDetail(),
            TextField::new('password')
                ->setFormType(PasswordType::class)
                ->setLabel('Clave')
                ->setFormTypeOptions([
                    'row_attr' => [
                        'data-controller' => 'password-visibility',
                        'data-password-visibility-hidden-class' => 'd-none',
                    ],
                    'attr' => [
                        'data-password-visibility-target' => 'input',
                    ],
                ])
                ->onlyOnForms()
                ->setColumns('col-12 col-lg-4'),
            AssociationField::new('role')
                ->setLabel('Roles')
                ->setColumns('col-12')
                ->onlyOnForms(),
            FormField::addPanel('Acciones')
                ->onlyWhenUpdating(),
            BooleanField::new('isDisabled')
                ->setLabel('Deshabilitado')
                ->setColumns('col-12 col-lg-3')
                ->setFormTypeOption(
                    'disabled',
                    $pageName !== Crud::PAGE_EDIT
                )
                ->onlyWhenUpdating(),
            BooleanField::new('isDeleted')
                ->setLabel('Baja')
                ->setColumns('col-12 col-lg-3')
                ->onlyWhenUpdating()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        // $totalReport = Action::new('totalReport')
        //     ->linkToUrl('#')
        //     ->setLabel('Reporte de totales')
        //     ->addCssClass('btn btn-success')
        //     ->setIcon('fa fa-download')
        //     ->createAsGlobalAction();

        return parent::configureActions($actions)
                    ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                        return $action
                                    ->setIcon('fa fa-plus')
                                    ->setLabel('Registrar');
                    })
                    // ->add(Crud::PAGE_INDEX, $totalReport)
                    ->add(Crud::PAGE_INDEX, Action::DETAIL)
                    ->remove(Crud::PAGE_INDEX, Action::DELETE)
                    ->remove(Crud::PAGE_DETAIL, Action::DELETE);
                ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return parent::configureFilters($filters)
                    ->add(TextFilter::new('username')->setLabel('Usuario'))
                    ->add(TextFilter::new('firstname')->setLabel('Nombre'))
                    ->add(TextFilter::new('lastname')->setLabel('Apellidos'))
                ;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return parent::configureAssets($assets)
                    ->addWebpackEncoreEntry('admin')
                    ->addWebpackEncoreEntry('app')
        ;
    }
}
