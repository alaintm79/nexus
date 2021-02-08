<?php

namespace App\Form\Logistica\Ejecucion;

use Symfony\Component\Form\AbstractType;
use App\Entity\Logistica\Contrato\Ejecucion;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EjecucionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('valorCup', MoneyType::class, [
                'label' => 'CUP',
                'currency' => 'USD',
                'required' => false
            ])
            ->add('valorCuc', MoneyType::class, [
                'label' => 'CUC',
                'currency' => 'USD',
                'required' => false
            ])
            ->add('observacion', TextareaType::class, [
                'label' => 'Observación',
                'required' => false
            ]);


        /*
         * EventListener
         */
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Ejecucion::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ejecucion';
    }


}
