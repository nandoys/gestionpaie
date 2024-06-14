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
        $j = 0; // index départ chunk
        $month = 0;
        $chunks = [];  
        for ($i=count($this->months)-1; $i > -1; $i--) { 
            if ($month > 2) {
                $month = 0;
                $j++;;
            }

            $this->chunks[$j][$month] = $this->months[$i];

            $month++;
        }


        return $this->chunks;
    }

}