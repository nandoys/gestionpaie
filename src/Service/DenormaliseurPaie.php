<?php

namespace App\Service;

use App\Entity\Indemnite;
use App\Entity\Paiement;
use App\Entity\Remuneration;
use App\Repository\AgentRepository;
use Doctrine\ORM\EntityManagerInterface;

class DenormaliseurPaie
{
    private $paiements = [];

    public function __construct(array $data, AgentRepository $repoAgent, EntityManagerInterface $em) {
        foreach ($data as $result) {
            $remuneration = new Remuneration();
            $remuneration->setBase($result['base'])
                ->setPrimeDiplome($result['primeDiplome'])
                ->setHeureSupplementaire($result['heureSupplementaire']);

            $indemnite = new Indemnite();
            $indemnite->setTransport($result['transport'])
                ->setLogement($result['logement'])
                ->setAllocationFamiliale($result['allocationFamiliale'])
                ->setAutres($result['autres']);

            $agent = $repoAgent->find($result['agent_id']);
            $paiement = new Paiement($remuneration, $indemnite, $agent, $em);

            $paiement->setAbscence($result['abscence'])
                ->setCnss($result['cnss'])
                ->setIpr($result['ipr'])
                ->setAvanceSalaire($result['avanceSalaire'])
                ->setPretLogement($result['pretLogement'])
                ->setPretFraisScolaire($result['pretFraisScolaire'])
                ->setPretDeuil($result['pretDeuil'])
                ->setPretAutre($result['pretAutre']);

            $this->paiements[] = $paiement;
        }
    }

    /**
     * @return array
     */
    public function getDenormalizedData(): array
    {
        return $this->paiements;
    }

}