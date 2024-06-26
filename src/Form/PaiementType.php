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
            ->add('avanceSalaire', null, [
                'disabled' => true
            ])
            ->add('pretLogement', null, [
                'disabled' => true
            ])
            ->add('pretFraisScolaire', null, [
                'disabled' => true
            ])
            ->add('pretDeuil', null, [
                'disabled' => true
            ])
            ->add('pretAutre', null, [
                'disabled' => true
            ])
            ->add('abscence')
            ->add('dateAt', null, [
                'widget' => 'single_text',
                'attr' => ['min' => $options['min'], 'max' =>$options['max']],
            ])
            ->add('base', null, [])
            ->add('primeDiplome', null, [])
            ->add('heureSupplementaire')
            ->add('transport', null, [])
            ->add('logement', null, [])
            ->add('allocationFamiliale', null, [])
            ->add('autres', null, [])
            ->add('exceptionnel', null, [])
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
