<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreMoisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filtreMois',DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker', 'data-date-start-date' => $options['minMoisFiltre'], 'placeholder' => 'Choisir un mois',
                    'data-date-end-date' =>$options['maxMoisFiltre']],
                'format' => 'M-y'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);

        $resolver->setRequired([
            'minMoisFiltre',
            'maxMoisFiltre',
        ]);
    }
}
