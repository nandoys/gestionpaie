<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Diplome;
use App\Entity\Fonction;
use App\Entity\EtatCivil;
use App\Entity\Nationalite;
use App\Repository\FonctionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class AgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('postnom')
            ->add('prenom')
            ->add('sexe', ChoiceType::class, [
                'choices'=>["Homme"=>"Homme", "Femme"=>"Femme"],
                'placeholder' => "Choisir sexe"
            ])
            ->add('dateNaissance', BirthdayType::class, [
                'widget' => 'single_text',
            ])
            ->add('lieuNaissance')
            ->add('etatCivil', EntityType::class, [
                'class'=>EtatCivil::class,
                'choice_label' => 'titre',
                'placeholder' => "Choisir Etat civil"
            ])
            ->add('debutContrat', null, [
                'widget' => 'single_text',
            ])
            ->add('finContrat', null, [
                'widget' => 'single_text',
            ])
            ->add('matricule')

            ->add('numeroCnss')
            ->add('nombreEnfant', null, [
                'attr'=>['min'=>0]
            ])
            ->add('fonction', EntityType::class, [
                'class'=>Fonction::class,
                'choice_label' => 'titre',
                'query_builder' => function (FonctionRepository $repo) {
                    return $repo->createQueryBuilder('f')
                        ->orderBy('f.titre', 'ASC');
                },
                'placeholder' => "Choisir une fonction"
            ])
            ->add('diplome', EntityType::class, [
                'class'=>Diplome::class,
                'choice_label' => 'titre',
                'placeholder' => "Choisir le niveau d'étude"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}
