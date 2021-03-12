<?php

namespace App\Controller\Logistica\Report\Traits;

use Symfony\Component\Form\Extension\Core\Type\TextType;

trait FormRangeTrait {

    /*
    *   Report Form
    */

    private function form(): ?object
    {
        return $this->createFormBuilder([])
            ->add('start', TextType::class, [
                'label' => 'Fecha Inicial',
            ])
            ->add('end', TextType::class, [
                'label' => 'Fecha Final',
            ])
            ->getForm();
    }

}
