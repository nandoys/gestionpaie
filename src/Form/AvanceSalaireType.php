<?php

namespace App\Form;

use App\Entity\AvanceSalaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvanceSalaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateAt', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('montant', null, [
                'attr' => ['placeholder' => "Montant de l'avance"]
            ])
            ->add('mensualite', null, [
                'attr' => ['placeholder' => 'Modalité de déduction']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AvanceSalaire::class,
        ]);
    }
}
