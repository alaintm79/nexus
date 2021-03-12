<?php

namespace App\Form\Logistica\Suplemento;

use Symfony\Component\Form\AbstractType;
use App\Entity\Logistica\Contrato\Suplemento;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AprobarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
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
                'label' => 'Comite',
            ])
            ->add('registroAcuerdo',IntegerType::class, [
                'label' => 'Acuerdo'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Suplemento::class,
            'validation_groups' => ['approve'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }
}
