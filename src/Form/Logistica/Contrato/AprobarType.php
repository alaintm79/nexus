<?php

namespace App\Form\Logistica\Contrato;

use Symfony\Component\Form\AbstractType;
use App\Entity\Logistica\Contrato\Contrato;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AprobarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaAprobado', DateType::class,[
                'widget'   => 'single_text',
                'required' => true,
                'label'    => 'Aprobado el...',
                'html5' => true,
            ])
            ->add('registroComite',IntegerType::class,[
                'label' => 'Comité',
            ])
            ->add('registroAcuerdo',IntegerType::class, [
                'label' => 'Acuerdo'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contrato::class,
            'validation_groups' => ['approve'],
        ]);
    }
}
