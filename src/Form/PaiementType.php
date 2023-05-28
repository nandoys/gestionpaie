<?php

namespace App\Form;

use App\Entity\Paiement;
use App\Event\PaieEventSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaiementType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cnss')
            ->add('ipr')
            ->add('avanceSalaire')
            ->add('pretLogement')
            ->add('pretFraisScolaire')
            ->add('pretDeuil')
            ->add('pretAutre')
            ->add('dateAt', null, [
                'widget' => 'single_text'
            ])
            ->add('base')
            ->add('primeDiplome')
            ->add('heureSupplementaire')
            ->add('transport')
            ->add('logement')
            ->add('allocationFamiliale')
            ->add('autres')
            ->addEventSubscriber(new PaieEventSubscriber())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}
