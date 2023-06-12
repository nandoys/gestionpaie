<?php

namespace App\Form;

use App\Entity\Remuneration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemunerationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('base')
            ->add('primeDiplome')
            ->add('heureSupplementaire')
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event){
                if($event->getForm()->getParent()->getName() == "agent_salaire") {
                    $event->getForm()->remove('heureSupplementaire');
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Remuneration::class,
        ]);
    }
}
