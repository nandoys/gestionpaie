<?php

namespace App\Form;

use App\Entity\Indemnite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndemniteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('transport')
            ->add('logement')
            ->add('allocationFamiliale')
            ->add('autres')
            ->add('exceptionnel')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Indemnite::class,
        ]);
    }
}
