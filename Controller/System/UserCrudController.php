<?php

namespace App\Controller\System;

use App\Entity\System\User;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
        $unit = $this->isGranted('ROLE_ADMIN') ? 'ALL' : $this->getUser()->getUnit()->getUnit();
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
                    ->leftJoin('entity.unit', 'u')
                    ->andWhere('entity.isDeleted = :deleted')
                    ->setParameter('deleted', false);

        if($unit !== 'ALL'){
            $qb->andWhere('u.unit = :unit')
                ->setParameter('unit', $unit);
            ;
        }

        return $qb;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
                    ->setPageTitle('index', function (){
                        return '<i class="menu-icon fa-fw fa fas fa-users"></i> Usuarios / Activos';
                    })
                    ->setPageTitle('new', function (){
                        return '<i class="menu-icon fa-fw fa fas fa-users"></i> Registrar';
                    })
                    ->setPageTitle('edit', function (){
                        return '<i class="menu-icon fa-fw fa fas fa-users"></i> Modificar';
                    })
                    ->setPageTitle('detail', function (User $user){
                        return '<i class="menu-icon fa-fw fa fas fa-users"></i> Usuario '.$user->getFirstName().' '.$user->getLastname();
                    })
                    ->setFormOptions(['error_mapping' => [
                        'ciValid' => 'idCard',
                        'accountValid' => 'username'
                    ]])
                    ->overrideTemplate('crud/index', 'crud/user.html.twig')
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
                ->setFormTypeOptions([
                    'attr' => [
                        'maxlength' => '11',
                        'pattern' => '\d*'
                    ]
                ])
                ->hideOnIndex(),
            AssociationField::new('unit')
                ->setLabel('Unidad')
                ->setRequired(true)
                ->setFormTypeOptions([
                    'placeholder' => ''
                ])
                ->setColumns('col-12 col-lg-4'),
            AssociationField::new('job')
                ->setLabel('Plaza')
                ->setRequired(true)
                ->setFormTypeOptions([
                    'placeholder' => ''
                ])
                ->setColumns('col-12 col-lg-4'),
            AssociationField::new('area')
                ->setLabel('Area')
                ->setRequired(true)
                ->setFormTypeOptions([
                    'placeholder' => ''
                ])
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
                ->hideOnIndex()
                ->hideWhenCreating()
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
