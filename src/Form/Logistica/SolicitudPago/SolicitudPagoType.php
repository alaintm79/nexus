<?php

namespace App\Form\Logistica\SolicitudPago;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SolicitudPagoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('noDocumentoPrimario', TextType::class, [
                'label' => 'Documento',
            ])
            ->add('fechaDocumento', DateType::class,[
                'widget'   => 'single_text',
                'required' => true,
                'label'    => 'Fecha documento',
                'html5' => true,
            ])
            ->add('contrato', EntityType::class, [
                'class' => 'App\Entity\Logistica\Contratos\Contrato',
                'label' => 'Contrato',
                'placeholder' => '',
                'choice_label'  => function ($entity) {
                    return \strtoupper($entity->getNumero().' - '.$entity->getProveedorCliente()->getNombre());
                },
                'query_builder' => function(EntityRepository $er){
                    return $er->queryContratosVigenteProveedores();
                }
            ])
            ->add('objetivo', TextareaType::class, [
                'label' => 'Objetivo',
            ])
            ->add('estado', EntityType::class, [
                'class' => 'App\Entity\Logistica\SolicitudPago\Estado',
                'label' => 'Estado',
                'query_builder' => function(EntityRepository $er) use ($options){
                    return $er->findByEstado($options['estado']);
                }
            ])
            ->add('acapite', EntityType::class, [
                'class' => 'App\Entity\Logistica\SolicitudPago\Acapite',
                'label' => 'Acapite',
                'placeholder' => '',
                'required' => false
            ])
            ->add('tipoDocumento', EntityType::class, [
                'class' => 'App\Entity\Logistica\SolicitudPago\TipoDocumento',
                'label' => 'Tipo documento',
                'placeholder' => '',
            ])
            ->add('tipoPago', EntityType::class, [
                'class' => 'App\Entity\Logistica\SolicitudPago\TipoPago',
                'label' => 'Tipo pago',
                'placeholder' => '',
            ])
            ->add('instrumentoPago', EntityType::class, [
                'class' => 'App\Entity\Logistica\SolicitudPago\InstrumentoPago',
                'label' => 'Instrumento pago',
                'placeholder' => '',
            ])
            ->add('importeCup', TextType::class, [
                'label' => 'Importe CUP',
                'required' => false,
                'attr' => [
                    'placeholder' => '0.00',
                ]
            ])
            ->add('importeCuc', TextType::class, [
                'label' => 'Importe CUC',
                'required' => false,
                'attr' => [
                    'placeholder' => '0.00',
                ]
            ])
            ->add('importeTotal', TextType::class, [
                'label' => 'Importe Total',
                'required' => false,
                'attr' => [
                    'placeholder' => '0.00',
                ]
            ])
            ->add('observacion', TextareaType::class, [
                'label' => 'Observación',
                'required' => false,
            ])
            ->add('documentoPrimario', FileType::class, [
                'label'        => 'Documento',
                'multiple'     => false,
                'required'     => false,
                'data_class' => null,
            ])
        ;

        /*
         * EventListener
         */

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($options) {
            $solicitud = $event->getData();
            $form = $event->getForm();

            if (! \in_array('PENDIENTE', $options['estado'])) {
                $form->remove('noDocumentoPrimario')
                    ->remove('fechaDocumento')
                    ->remove('contrato')
                    ->remove('objetivo')
                    ->remove('acapite')
                    ->remove('tipoDocumento')
                    ->remove('tipoPago')
                    ->remove('instrumentoPago')
                    ->remove('importeCup')
                    ->remove('importeCuc')
                    ->remove('importeTotal')
                    ->remove('documentoPrimario')
                ;
            }

            if (\in_array('PAGADO', $options['estado']) && $solicitud->getTipoDocumento()->getTipo() !== 'FACTURA') {
                $form->add('noDocumentoSecundario', TextType::class, [
                    'label' => 'No. Factura',
                    'attr' => [
                        'disabled' => 'disabled'
                    ]
                ])
                ->add('documentoSecundario', FileType::class, [
                    'label'        => 'Documento secundario',
                    'multiple'     => false,
                    'required'     => false,
                    'attr' => [
                        'disabled' => 'disabled'
                    ],
                    'data_class' => null,
                ]);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Logistica\SolicitudPago\SolicitudPago',
            'estado' => []
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
