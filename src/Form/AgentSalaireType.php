<?php

namespace App\Form;

use App\Entity\Agent;
use App\Form\AgentType;
use App\Entity\Indemnite;
use App\Form\IndemniteType;
use App\Entity\Remuneration;
use App\Form\RemunerationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentSalaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('agent', AgentType::class, [
                'data_class'=>Agent::class
            ])
            ->add('remuneration', RemunerationType::class, [
                'data_class'=>Remuneration::class
            ])
            ->add('indemnite', IndemniteType::class, [
                'data_class'=>Indemnite::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class'=>null,
        ]);
    }
}
