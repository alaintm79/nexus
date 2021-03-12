<?php

namespace App\Form\Logistica\Suplemento;

use App\Entity\Logistica\Contrato\Suplemento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CancelarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaCancelado', DateType::class, [
                'widget'   => 'single_text',
                'required' => true,
                'label'    => 'Cancelado el...',
                'html5' => true,
            ])
            ->add('canceladoComite',TextType::class,[
                    'label' => 'Comite',
                    'required' => true,
                ])
            ->add('canceladoAcuerdo',TextType::class, [
                'label' => 'Acuerdo',
                'required' => true,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Suplemento::class,
            'validation_groups' => ['cancel'],
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
