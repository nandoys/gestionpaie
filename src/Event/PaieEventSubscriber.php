<?php

namespace App\Event;

use App\Repository\AvanceSalaireRepository;
use App\Repository\PaiementRepository;
use App\Repository\PretAgentRepository;
use App\Service\DeducteurSalaire;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PaieEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private PaiementRepository $repoPaie, private AvanceSalaireRepository $repoAvance,
                                private PretAgentRepository $repoPret){}

    public static function getSubscribedEvents() : array
    {
        return [FormEvents::PRE_SET_DATA  => 'deduction'];
    }

    public function deduction(FormEvent $event) : void
    {

        $paiement = $event->getData();

        $avance = $this->repoAvance->findUnpaidAvanceSalaire($paiement->getAgent(), $paiement->getDateAt());

        if ($avance !== NULL && $paiement->getId() === NULL) {
            $total_montant = 0;
            foreach ($avance as $key => $value) {
                $total_montant += $value->getMontant();
            }
            
            $paiement->setAvanceSalaire($total_montant);
        }

        $pretLogement = $this->repoPret->findFirstUnpaidPret($paiement->getAgent(), 'Logement');

        if ($pretLogement !== NULL && $paiement->getId() === NULL) {
            $paiement->setPretLogement($pretLogement->calculDueMensuel());
        }

        $pretFraisScolaire = $this->repoPret->findFirstUnpaidPret($paiement->getAgent(), 'Frais scolaire');

        if ($pretFraisScolaire !== NULL && $paiement->getId() === NULL) {
            $paiement->setPretFraisScolaire($pretFraisScolaire->calculDueMensuel());
        }

        $pretDeuil = $this->repoPret->findFirstUnpaidPret($paiement->getAgent(), 'Deuil');

        if ($pretDeuil !== NULL && $paiement->getId() === NULL) {
            $paiement->setPretDeuil($pretDeuil->calculDueMensuel());
        }

        $pretAutres = $this->repoPret->findFirstUnpaidPret($paiement->getAgent(), 'Autres');

        if ($pretAutres !== NULL && $paiement->getId() === NULL) {
            $paiement->setPretAutre($pretAutres->calculDueMensuel());
        }

        

    }
}