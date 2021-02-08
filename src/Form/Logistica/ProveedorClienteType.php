<?php

namespace App\Form\Logistica;

use App\Entity\Logistica\ProveedorCliente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProveedorClienteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoReup', TextType::class, [
                'label' => 'Código REUP',
                'required' => false
            ])
            ->add('isProveedor', CheckboxType::class, [
                'label' => 'NO / SI',
                'required' => false,
                'label' => false,
            ])
            ->add('isCliente', CheckboxType::class, [
                'label' => 'NO / SI',
                'required' => false,
                'label' => false,
            ])
            ->add('cuentaCup', TextType::class, [
                'label' => 'Cuenta CUP',
                'required' => false
            ])
            ->add('titularCuentaCup', TextType::class, [
                'label' => 'Títular Cuenta CUP',
                'required' => false
            ])
            ->add('observacion', TextareaType::class, [
                'label' => 'Observación',
                'required' => false
            ]);
            // ->add('cuentaCuc', TextType::class, [
            //     'label' => 'Cuenta CUC',
            //     'required' => false
            // ])
            // ->add('titularCuentaCuc', TextType::class, [
            //     'label' => 'Títular Cuenta CUC',
            //     'required' => false
            // ])

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $proveedorCliente = $event->getData();
            $form    = $event->getForm();

            if($proveedorCliente->getIsModificable()){
                $form->add('nombre', TextType::class);
            } else {
                $form->remove('nombre', TextType::class);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ProveedorCliente::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'p_c';
    }
}
