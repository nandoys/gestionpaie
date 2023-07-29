<?php

namespace App\Service;

use App\Entity\Agent;
use App\Entity\Paiement;
use App\Repository\PaiementRepository;

class DeducteurSalaire
{
    public function __construct(PaiementRepository $repoPaie, private Paiement $paiement, int $paieId = 0)
    {
        $this->paiementsExistants = $repoPaie->findPaymentsByDate(
            $this->paiement->getDateAt()->format('m'), $this->paiement->getAgent(), $paieId
        );
    }

    public function deduire() : void
    {
        // $deductionsPrecedentes = 0;

        // foreach ($this->paiementsExistants as $paiementExistant)
        // {
        //     $deductionsPrecedentes += $paiementExistant->calculDeduction();
        // }

        // $this->paiement->setDeductionPrecedente($deductionsPrecedentes);

    }
}