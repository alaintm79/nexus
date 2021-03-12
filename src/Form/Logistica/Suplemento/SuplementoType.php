<?php

namespace App\Form\Logistica\Suplemento;

use App\Entity\Logistica\Contrato\Suplemento;
use App\Entity\Logistica\Contrato\Vigencia;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuplementoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('objeto', TextareaType::class, [
                'required' => true
            ])
            ->add('valorCup', TextType::class, [
                'label' => 'CUP',
                'required' => false
            ])
            ->add('vigencia', EntityType::class, [
                'class' => Vigencia::class,
                'label' => 'Vigencia',
                'required' => false,
                'placeholder' => '',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('v')
                        ->orderBy('v.orden', 'ASC')
                    ;
                }
            ])
            ->add('fechaVigencia', DateType::class,[
                'widget'   => 'single_text',
                'required' => true,
                'label'    => 'Fecha de vigencia',
                'html5' => true,
            ])
            ->add('observacion', TextareaType::class, [
                'label' => 'Observación',
                'required' => false
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Suplemento::class,
            'validation_groups' => ['new', 'edit'],
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
