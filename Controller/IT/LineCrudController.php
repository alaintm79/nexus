<?php

namespace App\Controller\IT;

use App\Entity\IT\Line;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LineCrudController extends AbstractCrudController
{
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $unit = $this->isGranted('ROLE_ADMIN') ? 'ALL' : $this->getUser()->getUnit()->getUnit();
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
                    ->leftJoin('entity.state', 's')
                    ->leftJoin('entity.username', 'un')
                    ->leftJoin('un.unit', 'u')
                    ->addOrderBy('u.unit', 'ASC')
                    ;

        $qb->andWhere($qb->expr()->in('s.state', ':state'))
            ->setParameter('state', ['Activo', 'Reserva']);

        if($unit !== 'ALL'){
            $qb->andWhere('u.unit = :unit')
                ->setParameter('unit', $unit);
            ;
        }

        return $qb;
    }

    public static function getEntityFqcn(): string
    {
        return Line::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
                    ->setPageTitle('index', function (){
                        return '<i class="fa fas fa-sim-card"></i> Líneas / Asignadas';
                    })
                    ->setPageTitle('new', function (){
                        return '<i class="fa fas fa-sim-card"></i> Registrar';
                    })
                    ->setPageTitle('edit', function (){
                        return '<i class="fa fas fa-sim-card"></i> Modificar';
                    })
                    ->setPageTitle('detail', function (Line $line){
                        return '<i class="fa fas fa-sim-card"></i> Línea '.$line->getNumber();
                    })
                    ->setFormOptions(['error_mapping' => [
                            'deletedValid' => 'state',
                            'deletedAtValid' => 'deletedAt',
                    ]])
                    ->setSearchFields(
                        [
                            'number',
                            'pin',
                            'puk',
                            'voicePlan.plan',
                            'dataPlan.plan',
                            'state.state',
                            'username.firstname',
                            'username.lastname',
                            'username.unit.unit',
                            'observation'
                        ]
                    )
                    ->overrideTemplate('crud/index', 'crud/line.html.twig')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $unit = $this->isGranted('ROLE_ADMIN') ? 'ALL' : $this->getUser()->getUnit()->getUnit();

        return [
            TextField::new('number', 'Número')
            ->setFormTypeOptions([
                    'attr' => ['maxlength' => '8', 'minlength' => '8', 'pattern' => '\d*']
                ])
                ->setColumns('col-12 col-lg-3'),
            TextField::new('pin', 'PIN')
                ->setFormTypeOptions([
                    'attr' => ['maxlength' => '4', 'minlength' => '4', 'pattern' => '\d*' ]])
                ->setColumns('col-12 col-lg-3'),
            TextField::new('puk', 'PUK')
                ->setFormTypeOptions([
                    'attr' => ['maxlength' => '8', 'minlength' => '8', 'pattern' => '\d*']
                ])
                ->hideOnIndex()
                ->setColumns('col-12 col-lg-3'),
            AssociationField::new('state', 'Estado')
                ->setFormTypeOptions([
                    'query_builder' => function (EntityRepository $er) {
                        $qb = $er->createQueryBuilder('s');
                        return $qb
                            ->andWhere($qb->expr()->notIn('s.state', ':state'))
                            ->setParameter('state', ['Roto', 'En taller', 'Sin consumible']);
                        },
                    'placeholder' => '',
                ])
                ->setColumns('col-12 col-lg-3'),
            AssociationField::new('voicePlan', 'Plan de voz')
                ->setFormTypeOptions([
                    'placeholder' => '',
                ])
                ->setColumns('col-12 col-lg-4'),
            AssociationField::new('dataPlan', 'Plan de datos')
                ->setFormTypeOptions([
                    'placeholder' => '',
                ])
                ->setColumns('col-12 col-lg-4'),
            TextField::new('username.unit', 'Unidad')
                ->hideOnForm(),
            AssociationField::new('username', 'Usuario')
                ->setFormTypeOptions([
                    'query_builder' => function (EntityRepository $er) use ($unit){
                        return $er->findUsersByUnit($unit);
                    },
                    'group_by' => 'unit',
                    'placeholder' => '',
                    'required' => \true
                ])
                ->setColumns('col-12 col-lg-4'),
            MoneyField::new('additionalMin','Monto Min. adicionales')
                ->setCurrency('CUP')
                ->setNumDecimals('2')
                ->onlyOnForms()
                ->setColumns('col-12 col-lg-4'),
            MoneyField::new('additionalSms', 'Monto SMS adicionales')
                ->setCurrency('CUP')
                ->setNumDecimals('2')
                ->onlyOnForms()
                ->setColumns('col-12 col-lg-4'),
            TextareaField::new('observation', 'Observaciones')
                ->hideOnIndex()
                ->setColumns('col-12'),
            DateField::new('deletedAt', 'Fecha de baja')
                ->setColumns('col-12 col-lg-4')
                ->onlyWhenUpdating(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $logAction = Action::new('log', 'log')
                                ->linkToUrl(function(Line $line) {
                                    return $this->generateUrl('app_it_log', [
                                        'id' => $line->getId(),
                                        'type' => 'Line',
                                    ]);
                                })
                                ->setLabel('Registro de cambios')
                                ->setHtmlAttributes(['target' => '_blank']);
        $formAction = Action::new('form', 'form')
                                ->linkToUrl(function(Line $line) {
                                    return $this->generateUrl('app_it_mobile_user_form', [
                                        'id' => $line->getId(),
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
                        $action->displayIf(static function (Line $line) {
                            return \is_null($line->getDeletedAt());
                        });

                        return $action;
                    })
                    ->add(Crud::PAGE_INDEX, $logAction)
                    ->add(Crud::PAGE_DETAIL, $logAction)
                    ->update(Crud::PAGE_DETAIL, Action::EDIT, function(Action $action) {
                        $action->displayIf(static function (Line $line) {
                            return \is_null($line->getDeletedAt());
                        });

                        return $action;
                    })
                    ->reorder(Crud::PAGE_INDEX, [Action::DETAIL, Action::EDIT])
        ;
    }
}
