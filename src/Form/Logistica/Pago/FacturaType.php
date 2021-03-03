<?php

namespace App\Form\Logistica\Pago;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use App\Entity\Logistica\Contrato\Contrato;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FacturaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contrato', EntityType::class, [
                'class' => Contrato::class,
                'label' => 'Contrato',
                'required' => true,
                'placeholder' => '',
                'query_builder' => function(EntityRepository $er){
                    return $er->getArrayContratosByProveedores();
                },
                'choice_label'  => function ($contrato) {
                    return sprintf('%s: %s (%s: %s)', $contrato->getProveedorCliente(), $contrato->getNumero(), $contrato->getProcedencia(), $contrato->getCategoria());
                },
                'group_by' => 'proveedorCliente'
            ])
            ->add('factura', TextType::class, [
                'required' => true
            ])
            ->add('fecha', DateType::class,[
                'widget'   => 'single_text',
                'required' => true,
                'html5' => true,
            ])
            ->add('objetivo', TextareaType::class,[
                'required' => true,
            ])
            ->add('afecta', TextType::class,[
                'required' => false,
            ])
            ->add('valorCup', TextType::class, [
                'label' => 'Valor CUP',
                'required' => false
            ])
            ->add('valorCuc', TextType::class, [
                'label' => 'Valor CUC',
                'required' => false
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
            'data_class' => Factura::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'factura';
    }
}
