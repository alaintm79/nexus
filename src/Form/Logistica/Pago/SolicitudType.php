<?php

namespace App\Form\Logistica\Pago;

use App\Entity\Logistica\Contrato\Contrato;
use App\Entity\Logistica\Pago\Acapite;
use App\Entity\Logistica\Pago\Documento;
use App\Entity\Logistica\Pago\Instrumento;
use App\Entity\Logistica\Pago\Solicitud;
use App\Entity\Logistica\Pago\Tipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SolicitudType extends AbstractType
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
                'class' => Contrato::class,
                'label' => 'Contrato',
                'placeholder' => '',
                'choice_label'  => function ($entity) {
                    return \strtoupper($entity->getNumero().' - '.$entity->getProveedorCliente()->getNombre());
                },
                'query_builder' => function(EntityRepository $er){
                    return $er->findContratosVigenteProveedores();
                }
            ])
            ->add('objetivo', TextareaType::class, [
                'label' => 'Objetivo',
            ])
            ->add('acapite', EntityType::class, [
                'class' => Acapite::class,
                'label' => 'Acapite',
                'placeholder' => '',
                'required' => false
            ])
            ->add('tipoDocumento', EntityType::class, [
                'class' => Documento::class,
                'label' => 'Tipo documento',
                'placeholder' => '',
            ])
            ->add('tipoPago', EntityType::class, [
                'class' => Tipo::class,
                'label' => 'Tipo pago',
                'placeholder' => '',
            ])
            ->add('instrumentoPago', EntityType::class, [
                'class' => Instrumento::class,
                'label' => 'Instrumento pago',
                'placeholder' => '',
            ])
            ->add('importeCup', TextType::class, [
                'label' => 'Importe',
                'required' => true
            ])
            ->add('observacion', TextareaType::class, [
                'label' => 'Observación',
                'required' => false,
            ])
            ->add('fileDocumentoPrimario', FileType::class, [
                'label' => 'Documento',
                'multiple' => false,
                'required' => false,
                'data_class' => null,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'image/jpeg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Por favor suba un archivo valido.',
                    ])
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Solicitud::class,
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
