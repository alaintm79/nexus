<?php

namespace App\Form\Sistema;

use App\Entity\Sistema\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioBajaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaBaja', DateType::class, [
                'widget'   => 'single_text',
                'required' => true,
                'label'    => 'Fecha baja',
                'html5' => true,
            ])
            ->add('observacion', TextareaType::class, [
                'required' => false,
                'label' => 'Observación'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
