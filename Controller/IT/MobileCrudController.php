<?php

namespace App\Controller\IT;

use App\Entity\IT\Mobile;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MobileCrudController extends AbstractCrudController
{
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $unit = $this->isGranted('ROLE_ADMIN') ? 'ALL' : $this->getUser()->getUnit()->getUnit();
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
                    ->addSelect("CONCAT(b.brand, '/', m.model) AS HIDDEN brand_model")
                    ->leftJoin('entity.state', 's')
                    ->leftJoin('entity.username', 'un')
                    ->leftJoin('entity.model', 'm')
                    ->leftJoin('un.unit', 'u')
                    ->leftJoin('m.brand', 'b')
                    ->addOrderBy('u.unit', 'ASC')
                    ->addOrderBy('brand_model', 'DESC')
                    ;

        $qb->andWhere($qb->expr()->in('s.state', ':state'))
            ->setParameter('state', ['Activo', 'Roto', 'En taller', 'Reserva']);

        if($unit !== 'ALL'){
            $qb->andWhere('u.unit = :unit')
                ->setParameter('unit', $unit);
            ;
        }

        return $qb;
    }

    public static function getEntityFqcn(): string
    {
        return Mobile::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
                    ->setPageTitle('index', function (){
                        return '<i class="fa fas fa-mobile"></i> Móviles / Asignados';
                    })
                    ->setPageTitle('new', function (){
                        return '<i class="fa fas fa-mobile"></i> Registrar';
                    })
                    ->setPageTitle('edit', function (){
                        return '<i class="fa fas fa-mobile"></i> Modificar';
                    })
                    ->setPageTitle('detail', function (Mobile $mobile){
                        return '<i class="fa fas fa-mobile"></i> Movil '.$mobile->getInventory();
                    })
                    ->setFormOptions(['error_mapping' => [
                            'deletedValid' => 'state',
                            'deletedAtValid' => 'deletedAt',
                    ]])
                    ->setSearchFields(
                        [
                            'inventory',
                            'imei',
                            'state.state',
                            'username.firstname',
                            'username.lastname',
                            'model.brand.brand',
                            'model.model',
                            'model.component',
                            'username.unit.unit'
                        ]
                    )
                    ->overrideTemplate('crud/index', 'crud/mobile.html.twig')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $unit = $this->isGranted('ROLE_ADMIN') ? 'ALL' : $this->getUser()->getUnit()->getUnit();

        return [
            TextField::new('inventory')
                ->setLabel('Inventario')
                ->setColumns('col-12 col-lg-4'),
            TextField::new('imei')
                ->setLabel('Imei')
                ->setColumns('col-12 col-lg-4'),
            AssociationField::new('model')
                ->setLabel('Marca / Modelo')
                ->setFormTypeOptions([
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('m')
                            ->leftJoin('m.brand', 'b')
                            ->leftJoin('m.device', 'd')
                            ->where("d.device = 'Celular'")
                            ->orderBy('b.brand', 'ASC')
                            ->addOrderBy('m.model', 'ASC');
                        },
                    'choice_label' => function ($model) {
                        return $model->getBrand().' / '.$model->getModel();
                    },
                    'placeholder' => '',
                ])
                ->onlyOnForms()
                ->setColumns('col-12 col-lg-4'),
            TextField::new('model.brandAndModel')
                ->setLabel('Marca / Modelo')
                ->hideOnForm(),
            ImageField::new('model.image')
                ->setLabel('Imagen')
                ->setBasePath('uploads/it/devices')
                ->hideOnForm(),
            AssociationField::new('state')
                ->setLabel('Estado')
                ->setFormTypeOptions([
                    'query_builder' => function (EntityRepository $er) {
                        $qb = $er->createQueryBuilder('s');
                        return $qb
                            ->where("s.state <> 'Sin consumible'");
                        },
                    'placeholder' => '',
                ])
                ->setColumns('col-12 col-lg-4'),
            TextField::new('username.unit')
                ->setLabel('Unidad')
                ->hideOnForm(),
            AssociationField::new('username')
                ->setLabel('Usuario')
                ->setFormTypeOptions([
                    'query_builder' => function (EntityRepository $er) use ($unit){
                        return $er->findUsersByUnit($unit);
                    },
                    'group_by' => 'unit',
                    'placeholder' => '',
                    'required' => \true
                ])
                ->setColumns('col-12 col-lg-4'),
            TextareaField::new('observation')
                ->setLabel('Observaciones')
                ->setColumns('col-12'),
            DateField::new('deletedAt')
                ->setLabel('Fecha de baja')
                ->setColumns('col-12 col-lg-4')
                ->onlyWhenUpdating(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $logAction = Action::new('log', 'log')
                                ->linkToUrl(function(Mobile $mobile) {
                                    return $this->generateUrl('app_it_log', [
                                        'id' => $mobile->getId(),
                                        'type' => 'Mobile',
                                    ]);
                                })
                                ->setLabel('Registro de cambios')
                                ->setHtmlAttributes(['target' => '_blank']);
        $formAction = Action::new('form', 'form')
                                ->linkToUrl(function(Mobile $mobile) {
                                    return $this->generateUrl('app_it_mobile_user_form', [
                                        'id' => $mobile->getId(),
                                    ]);
                                })
                                ->setLabel('Planilla')
                                ->setHtmlAttributes(['target' => '_blank']);

        return parent::configureActions($actions)
                    ->update(Crud::PAGE_INDEX, Action::NEW, static function (Action $action) {
                        return $action
                                    ->setIcon('fa fa-plus')
                                    ->setLabel('Registrar');
                    })
                    ->add(Crud::PAGE_INDEX, Action::DETAIL)
                    ->add(Crud::PAGE_INDEX, $formAction)
                    ->add(Crud::PAGE_DETAIL, $formAction, function(Action $action) {
                        $action->displayIf(static function (Mobile $mobile) {
                            return \is_null($mobile->getDeletedAt());
                        });

                        return $action;
                    })
                    ->add(Crud::PAGE_INDEX, $logAction)
                    ->add(Crud::PAGE_DETAIL, $logAction)
                    ->update(Crud::PAGE_DETAIL, Action::EDIT, function(Action $action) {
                        $action->displayIf(static function (Mobile $mobile) {
                            return \is_null($mobile->getDeletedAt());
                        });

                        return $action;
                    })
                    ->reorder(Crud::PAGE_INDEX, [Action::DETAIL, Action::EDIT])
        ;
    }
}
