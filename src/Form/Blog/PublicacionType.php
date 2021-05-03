<?php

namespace App\Form\Blog;

use App\Entity\Blog\Estado;
use App\Entity\Blog\Categoria;
use App\Entity\Blog\Publicacion;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PublicacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $estado = $options['estado'];
        $builder
            ->add('titulo', TextType::class, [
                'label' => 'Título'
            ])
            ->add('resumen', TextareaType::class)
            ->add('contenido', CKEditorType::class,[
                'required' => true,
            ])
            ->add('categoria', EntityType::class, [
                'class' => Categoria::class,
                'label' => 'Categoría',
                'required' => true,
            ])
            ->add('estado', EntityType::class, [
                'class' => Estado::class,
                'required' => true,
                'query_builder' => function (EntityRepository $er) use ($estado) {
                    return $er->createQueryBuilder('e')
                                ->where('e.estado != :estado')
                                ->setParameter('estado', $estado != 'Eliminado' ? 'Eliminado' : '%')
                                ->addOrderBy('e.id','ASC');
                }
            ])
            ->add('fechaPublicacion', DateType::class,[
                'widget'   => 'single_text',
                'required' => true,
                'label'    => 'Publicado el',
                'html5' => true,
            ])
            ->add('isSticky', CheckboxType::class, [
                'required' => false,
                'label' => 'Es relevante',
                'label_attr' => ['class' => 'switch-custom'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Publicacion::class,
            'estado' => null,
        ]);
    }
}
