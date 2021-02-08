<?php

namespace App\Form\Logistica\Contrato;

use Symfony\Component\Form\AbstractType;
use App\Entity\Logistica\Contrato\Contrato;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class FirmaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaFirma', DateType::class, [
                'widget'   => 'single_text',
                'required' => true,
                'label'    => 'Firmado el...',
                'html5' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contrato::class,
            'validation_groups' => ['firm'],
        ]);
    }
}
