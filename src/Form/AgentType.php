<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Diplome;
use App\Entity\Fonction;
use App\Entity\EtatCivil;
use App\Entity\Nationalite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('postnom')
            ->add('prenom')
            ->add('dateNaissance')
            ->add('lieuNaissance')
            ->add('debutContrat')
            ->add('finContrat')
            ->add('matricule')
            ->add('sexe', ChoiceType::class, [
                'choices'=>["Homme"=>"Homme", "Femme"=>"Femme"],
                'placeholder' => "Choisir sexe"
            ])
            ->add('numeroCnss')
            ->add('nombreEnfant')
            ->add('fonction', EntityType::class, [
                'class'=>Fonction::class,
                'choice_label' => 'titre',
                'placeholder' => "Choisir une fonction"
            ])
            ->add('diplome', EntityType::class, [
                'class'=>Diplome::class,
                'choice_label' => 'titre',
                'placeholder' => "Choisir le niveau d'étude"
            ])
            ->add('etatCivil', EntityType::class, [
                'class'=>EtatCivil::class,
                'choice_label' => 'titre',
                'placeholder' => "Choisir Etat civil"
            ])
            ->add('nationalite', EntityType::class, [
                'class'=>Nationalite::class,
                'choice_label' => 'titre',
                'placeholder' => "Choisir nationalité"
            ])
            ->add('Creer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}
