<?php

namespace App\Form\Contacto;

use App\Entity\Sistema\Pais;
use App\Entity\Contacto\Perfil;
use App\Entity\Contacto\Contacto;
use App\Entity\Contacto\Ubicacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class)
            ->add('apellidos', TextType::class)
            ->add('direccion', TextType::class, [
                'required' => false
            ])
            ->add('ci', TextType::class, [
                'required' => false,
                'label' => 'CI',
            ])
            ->add('pais', EntityType::class, [
                'class' => Pais::class,
                'label' => 'País',
                'required' => true,
            ])
            ->add('ubicacion', EntityType::class, [
                'class' => Ubicacion::class,
                'label' => 'Trabaja en',
                'required' => true,
                'placeholder' => '',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->addOrderBy('u.nombre','ASC');
                }
            ])
            ->add('telefonoMovil', TextType::class, [
                'required' => false,
                'label' => 'Celular'
            ])
            ->add('telefonoFijo', TextType::class, [
                'required' => false,
                'label' => 'Teléfono'
            ])
            ->add('telefonoFijoTrabajo', TextType::class, [
                'required' => false,
                'label' => 'Teléfono Trabajo'
            ])
            ->add('extension', TextType::class, [
                'required' => false,
                'label' => 'Extensión'
            ])
            ->add('correo1', TextType::class, [
                'required' => false,
                'label' => 'Correo #1'
            ])
            ->add('correo2', TextType::class, [
                'required' => false,
                'label' => 'Correo #2'
            ])
            ->add('direccionTrabajo', TextType::class, [
                'required' => false,
                'label' => 'Dirección Trabajo'
            ])
            ->add('observacion', TextareaType::class, [
                'required' => false
            ])
            ->add('perfil', EntityType::class, [
                'class' => Perfil::class,
                'placeholder' => '',
                'required' => false
            ])
            ->add('cargo', TextType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contacto::class,
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
