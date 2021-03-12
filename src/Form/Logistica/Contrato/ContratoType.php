<?php

namespace App\Form\Logistica\Contrato;

use App\Entity\Logistica\Contrato\Categoria;
use App\Entity\Logistica\Contrato\Contrato;
use App\Entity\Logistica\Contrato\Vigencia;
use App\Entity\Logistica\ProveedorCliente;
use App\Entity\Sistema\Unidad;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tipo = $options['tipo'];

        $builder
            ->add('categoria', EntityType::class, [
                'class' => Categoria::class,
                'label' => 'Tipo',
                'placeholder' => '',
                'query_builder' => function(EntityRepository $er) use ($tipo){
                    return $er->createQueryBuilder('c')
                        ->where('c.tipo = :tipo')
                        ->setParameter('tipo', $tipo === 'proveedor' ? 'p' : 'c')
                    ;
                }
            ])
            ->add('objeto', TextareaType::class, [
                'required' => true
            ])
            ->add('procedencia', EntityType::class, [
                'class' => Unidad::class,
                'label' => 'Procedencia',
                'placeholder' => '',
            ])
            ->add('proveedorCliente', EntityType::class, [
                'class' => ProveedorCliente::class,
                'label' => \ucfirst($tipo),
                'placeholder' => '',
                'query_builder' => function(EntityRepository $er) use ($tipo){
                    return $er->findByTipo($tipo);
                }
            ])
            ->add('valorCup', TextType::class, [
                'label' => 'Valor',
                'required' => false
            ])
            ->add('vigencia', EntityType::class, [
                'class' => Vigencia::class,
                'label' => 'Vigencia por...',
                'placeholder' => '',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('v')
                        ->orderBy('v.orden', 'ASC');
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
        $resolver->setDefaults([
            'data_class' => Contrato::class,
            'tipo' => null,
            'validation_groups' => ['new', 'edit'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }
}
