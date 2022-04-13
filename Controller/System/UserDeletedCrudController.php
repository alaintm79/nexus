<?php

namespace App\Controller\System;

use App\Entity\System\User;
use ArrayIterator;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;

class UserDeletedCrudController extends UserCrudController
{
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
                    ->andWhere('entity.isDeleted = :deleted')
                    ->setParameter('deleted', true);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
                    ->setPageTitle('index', function (){
                        return 'Usuarios bajas';
                    })
                    ->setPageTitle('detail', function (User $user){
                        return 'Usuario '.$user->getFirstName().' '.$user->getLastname();
                    })
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = parent::configureFields($pageName);
        $fields[] = BooleanField::new('isDeleted')
                        ->setLabel('Baja')
                        ->setDisabled()
                        ->hideOnIndex();

        return $fields;
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
