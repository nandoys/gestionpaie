<?php

namespace App\Form;

use App\Entity\Agent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('sexe')
            ->add('numeroCnss')
            ->add('nombreEnfant')
            ->add('fonction')
            ->add('diplome')
            ->add('etatCivil')
            ->add('nationalite')
            ->add('remuneration')
            ->add('indemnite')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}
