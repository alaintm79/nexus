<?php

namespace App\Controller\System;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;

class UserDeletedCrudController extends UserCrudController
{
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $unit = $this->isGranted('ROLE_ADMIN') ? 'ALL' : $this->getUser()->getUnit()->getUnit();
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
                    ->andWhere('entity.isDeleted = :deleted')
                    ->setParameter('deleted', true);

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
                        return '<i class="menu-icon fa-fw fa fas fa-users"></i> Usuarios / Bajas';
                    })
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
                    ->remove(Crud::PAGE_INDEX, Action::NEW)
                    ->remove(Crud::PAGE_INDEX, Action::EDIT)
                    ->remove(Crud::PAGE_DETAIL, Action::EDIT)
        ;
    }
}
