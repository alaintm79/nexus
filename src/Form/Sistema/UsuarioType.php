<?php

namespace App\Form\Sistema;

use App\Entity\Sistema\Usuario;
use App\Entity\Sistema\Plaza;
use App\Entity\Sistema\Unidad;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UsuarioType extends AbstractType
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $unidad = $options['unidad'];
        $action = $options['action'];

        $builder
            ->add('nombre', TextType::class)
            ->add('apellidos', TextType::class)
            ->add('ci', TextType::class, [
                'label' => 'CI',
            ])
            ->add('unidad', EntityType::class, [
                'class' => Unidad::class,
                'label' => 'Ubicación',
                'query_builder' => function(EntityRepository $er) use ($unidad){
                    return $er->findByNombre($unidad);
                },
            ])
            ->add('plaza', EntityType::class, [
                'class' => Plaza::class,
                'label' => 'Plaza',
                'placeholder' => '',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')->addOrderBy('p.nombre','ASC');
                }
            ])
            ->add('fechaAlta', DateType::class,[
                'widget'   => 'single_text',
                'required' => true,
                'label'    => 'Fecha Alta',
                'html5' => true,
            ])
            ->add('observacion', TextareaType::class, [
                'required' => false,
                'label' => 'Observación'
            ])
            ->add('hasAccount', CheckboxType::class, [
                    'required' => false,
                    'label' => false,
                ])
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'Usuario',
            ])
            ->add('correo', TextType::class, [
                'label' => 'Cuenta de Correo',
                'required' => false,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Clave',
                ],
                'second_options' => ['label' => 'Repetir Clave'],
                'required' => $action == 'new' ? true : false,
            ])
            ->add('servicio', ChoiceType::class, [
                'choices' => $this->getServicios(),
                'multiple' => true,
                'label' => 'Servicios Autorizados',
                'required' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => $this->getRoles(),
                'multiple' => true,
                'label' => 'Rol',
                'required' => false,
            ])
        ;
    }

    private function getRoles()
    {
        return $this->em->getRepository('App:Sistema\Rol')->findRoles();
    }

    private function getServicios()
    {
        return $this->em->getRepository('App:Sistema\Servicio')->findServicios();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
            'error_mapping' => [
                'ciValid' => 'ci',
            ],
            'validation_groups' => ['registration'],
            'unidad' => null,
            'action' => null,
        ]);
    }
}
