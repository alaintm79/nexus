<?php

namespace App\Repository\Traits;


trait BootstrapTableTrait {

    public function sort($qb, $columns, $params)
    {
        if(isset($params['sort']) && !\is_null($params['sort'])){
            $qb->orderBy($columns[$params['sort']], $params['order']);
        }
    }

    public function search($qb, $columns, $params)
    {
        if(isset($params['search']) && !empty($params['search'])){
            foreach($params['searchable'] as $column){
                $qb->orWhere('LOWER(CAST('.$columns[$column].' as text)) LIKE :search'.\ucfirst($column))
                    ->setParameter('search'.\ucfirst($column), '%'.$params['search'].'%');
            }
        }
    }

    public function filter($qb, $columns, $params)
    {
        if(isset($params['filter'])){
            $filters = \json_decode($params['filter'], true);

            foreach($filters as $filter => $value){
                $qb->andWhere('LOWER(CAST('.$columns[$filter].' as text)) LIKE :filter'.\ucfirst($filter))
                    ->setParameter('filter'.\ucfirst($filter), '%'.$value.'%');
            }
        }
    }
}
