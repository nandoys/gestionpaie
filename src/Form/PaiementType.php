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
    public function __construct(
        private PaieEventSubscriber $subscriber,
    ) {
    }


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
            ->add('abscence')
            ->add('dateAt', null, [
                'widget' => 'single_text',
                'attr' => ['min' => $options['min'], 'max' =>$options['max']],
            ])
            ->add('base', null, [
                'disabled' => true
            ])
            ->add('primeDiplome', null, [
                'disabled' => true
            ])
            ->add('heureSupplementaire', null, [
                'disabled' => true
            ])
            ->add('transport', null, [
                'disabled' => true
            ])
            ->add('logement', null, [
                'disabled' => true
            ])
            ->add('allocationFamiliale', null, [
                'disabled' => true
            ])
            ->add('autres', null, [
                'disabled' => true
            ])
            ->addEventSubscriber($this->subscriber)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);

        $resolver->setRequired([
            'min',
            'max',
        ]);
    }
}
