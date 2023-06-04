<?php

namespace App\Event;

use App\Repository\PaiementRepository;
use App\Service\DeducteurSalaire;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PaieEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private PaiementRepository $repoPaie){}

    public static function getSubscribedEvents() : array
    {
        return [FormEvents::PRE_SET_DATA  => 'deduction'];
    }

    public function deduction(FormEvent $event) : void
    {

        $paiement = $event->getData();

        if ($paiement->getId() === NULL){
            $deducteur = new DeducteurSalaire($this->repoPaie, $paiement);
        } else {
            $deducteur = new DeducteurSalaire($this->repoPaie, $paiement, $paiement->getId());
        }
        $deducteur->deduire();

    }
}