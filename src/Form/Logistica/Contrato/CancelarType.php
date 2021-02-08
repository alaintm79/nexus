<?php

namespace App\Form\Logistica\Contrato;

use Symfony\Component\Form\AbstractType;
use App\Entity\Logistica\Contrato\Contrato;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CancelarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaCancelado', DateType::class, [
                'widget'   => 'single_text',
                'required' => true,
                'label'    => 'Cancelado el...',
                'html5' => true,
            ])
            ->add('canceladoComite',IntegerType::class,[
                'label' => 'Comité',
                'required' => true,
            ])
            ->add('canceladoAcuerdo',IntegerType::class, [
                'label' => 'Acuerdo',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contrato::class,
            'validation_groups' => ['cancel'],
        ]);
    }
}
