<?php

namespace App\Controller\IT;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;

class MobileDeletedCrudController extends MobileCrudController
{
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $unit = $this->isGranted('ROLE_ADMIN') ? 'ALL' : $this->getUser()->getUnit()->getUnit();
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
                    ->andWhere('s.state = :state')
                    ->setParameter('state', 'Baja');

        $qb->andWhere($qb->expr()->in('s.state', ':state'))
            ->setParameter('state', ['Baja']);

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
                        return '<i class="menu-icon fa-fw fa fas fa-mobile"></i> Móviles / Bajas técnicas';
                    })
                    ->setEntityPermission('ROLE_ADMIN')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $formAction = Action::new('form', 'form');

        return parent::configureActions($actions)
                    ->remove(Crud::PAGE_INDEX, Action::NEW)
                    ->remove(Crud::PAGE_INDEX, Action::EDIT)
                    ->remove(Crud::PAGE_DETAIL, Action::EDIT)
                    ->remove(Crud::PAGE_DETAIL, $formAction)
                    ->remove(Crud::PAGE_INDEX, $formAction)
        ;
    }
}
