<?php

namespace App\Service;

class DateRange
{
    private $months = [];

    private $chunks;

    public function getMonthsInRange(\DateTime $debut, \DateTime $fin): array
    {
        $debutMois = (int) $debut->format('m');
        $debutAnnee = (int)$debut->format('Y');

        $finMois = (int) $fin->format('m');
        $finAnnee = (int) $fin->format('Y');

        for ($annee = $finAnnee; $annee >= $debutAnnee; $annee--) {
            for ($mois = 12; $mois >= 1; $mois--) {
                if ($annee === $debutAnnee && $mois >= $debutMois || $annee === $finAnnee && $mois <= $finMois) {
                    $this->months[] = ['mois' => $mois, 'annee' => $annee];
                }
                else if ($annee > $debutAnnee && $annee < $finAnnee) {
                    $this->months[] = ['mois' => $mois, 'annee' => $annee];
                }
            }
        }

        return $this->months;
    }

    public function splitIntoChunks(int $chunk): array
    {
        $this->chunks = array_chunk($this->months, $chunk);

        return $this->chunks;
    }

}