<?php

namespace App\Form\Sistema;

use App\Entity\Sistema\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $hasAccount = $options['has_account'];

        $builder
            ->add('nombre', TextType::class)
            ->add('apellidos', TextType::class)
            ->add('ci', TextType::class, [
                'label' => 'CI',
            ])
            ->add('unidad', EntityType::class, [
                'class' => 'App\Entity\Sistema\Unidad',
                'label' => 'Ubicación',
                'query_builder' => function(EntityRepository $er) use ($unidad){
                    return $er->findByNombre($unidad);
                },
            ])
            ->add('plaza', EntityType::class, [
                'class' => 'App\Entity\Sistema\Plaza',
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
                    'label' => 'Crear',
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
                'required' => true,
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

        /*
         * EventListener
         */

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $usuario = $event->getData();
            $form = $event->getForm();

            if (null !== $usuario->getId()) {

                $form->add('isBaja', CheckboxType::class, [
                    'label' => 'No / Si',
                    'required' => false,
                ])
                ->add('fechaBaja', DateType::class,[
                    'widget'   => 'single_text',
                    'required' => false,
                    'label'    => 'Fecha baja',
                    'html5' => true,
                ]);
            }
        });
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
                'usuarioSistemaValid' => 'roles',
            ],
            'unidad' => null,
            'has_account' => null
        ]);
    }
}
