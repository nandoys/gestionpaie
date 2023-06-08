<?php

namespace App\Form;

use App\Entity\PretAgent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PretAgentType extends AbstractType
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
            ->add('typePret', ChoiceType::class, [
                'choices' => ['Logement' => 'Logement', 'Frais scolaire' => 'Frais scolaire', 'Deuil' => 'Deuil', 'Autres' => 'Autres'],
                'placeholder' => 'Veuillez choisir le type de prêt'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PretAgent::class,
        ]);
    }
}
