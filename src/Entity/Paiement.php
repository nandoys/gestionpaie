<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PaiementRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' =>['read:paiements']],
        ),
    ]
)]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $cnss = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $ipr = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $avanceSalaire = null;

    #[ORM\Column(length: 255)]
    #[Groups('read:paiements')]
    private ?float $pretLogement = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $pretFraisScolaire = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $pretDeuil = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $pretAutre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups('read:paiements')]
    private ?\DateTimeInterface $dateAt = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $base = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $primeDiplome = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $heureSupplementaire = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $transport = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $logement = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $allocationFamiliale = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $autres = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('read:paiements')]
    private ?Agent $agent = null;

    #[ORM\Column(nullable: true)]
    #[Groups('read:paiements')]
    private ?float $abscence = null;
    private int $deductionPrecedente = 0;

    public function __construct(Remuneration $remuneration, Indemnite $indemnite, Agent $agent)
    {
        // définir l'agent à payer
        $this->agent = $agent;
        // Données sur la rémunération
        
        $this->base = $remuneration->getBase();
        $this->primeDiplome = $remuneration->getPrimeDiplome();
        $this->heureSupplementaire = $remuneration->getHeureSupplementaire();

        // Données des indemnités
        $this->transport = $indemnite->getTransport();
        $this->logement = $indemnite->getLogement();
        $this->allocationFamiliale = $indemnite->getAllocationFamiliale();
        $this->autres = $indemnite->getAutres();

        // Déduction salariale
        $this->cnss = 0;
        $this->ipr = 0;
        $this->avanceSalaire = 0;
        $this->pretLogement = 0;
        $this->pretFraisScolaire = 0;
        $this->pretDeuil = 0;
        $this->pretAutre = 0;
        $this->abscence = 0;

        $this->dateAt = new \DateTime();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCnss(): ?float
    {
        return $this->cnss;
    }

    public function setCnss(float $cnss): self
    {
        $this->cnss = $cnss;

        return $this;
    }

    public function getIpr(): ?float
    {
        return $this->ipr;
    }

    public function setIpr(float $ipr): self
    {
        $this->ipr = $ipr;

        return $this;
    }

    public function getAvanceSalaire(): ?float
    {
        return $this->avanceSalaire;
    }

    public function setAvanceSalaire(float $avanceSalaire): self
    {
        $this->avanceSalaire = $avanceSalaire;

        return $this;
    }

    public function getPretLogement(): ?float
    {
        return $this->pretLogement;
    }

    public function setPretLogement(float $pretLogement): self
    {
        $this->pretLogement = $pretLogement;

        return $this;
    }

    public function getPretFraisScolaire(): ?float
    {
        return $this->pretFraisScolaire;
    }

    public function setPretFraisScolaire(float $pretFraisScolaire): self
    {
        $this->pretFraisScolaire = $pretFraisScolaire;

        return $this;
    }

    public function getPretDeuil(): ?float
    {
        return $this->pretDeuil;
    }

    public function setPretDeuil(float $pretDeuil): self
    {
        $this->pretDeuil = $pretDeuil;

        return $this;
    }

    public function getPretAutre(): ?float
    {
        return $this->pretAutre;
    }

    public function setPretAutre(float $pretAutre): self
    {
        $this->pretAutre = $pretAutre;

        return $this;
    }

    public function getDateAt(): ?\DateTimeInterface
    {
        return $this->dateAt;
    }

    public function setDateAt(\DateTimeInterface $dateAt): self
    {
        $this->dateAt = $dateAt;

        return $this;
    }

    public function getBase(): ?float
    {
        return $this->base;
    }

    public function setBase(float $base): self
    {
        $this->base = $base;

        return $this;
    }

    public function getPrimeDiplome(): ?float
    {
        return $this->primeDiplome;
    }

    public function setPrimeDiplome(float $primeDiplome): self
    {
        $this->primeDiplome = $primeDiplome;

        return $this;
    }

    public function getHeureSupplementaire(): ?float
    {
        return $this->heureSupplementaire;
    }

    public function setHeureSupplementaire(float $heureSupplementaire): self
    {
        $this->heureSupplementaire = $heureSupplementaire;

        return $this;
    }

    public function getTransport(): ?float
    {
        return $this->transport;
    }

    public function setTransport(float $transport): self
    {
        $this->transport = $transport;

        return $this;
    }

    public function getLogement(): ?float
    {
        return $this->logement;
    }

    public function setLogement(float $logement): self
    {
        $this->logement = $logement;

        return $this;
    }

    public function getAllocationFamiliale(): ?float
    {
        return $this->allocationFamiliale;
    }

    public function setAllocationFamiliale(float $allocationFamiliale): self
    {
        $this->allocationFamiliale = $allocationFamiliale;

        return $this;
    }

    public function getAutres(): ?float
    {
        return $this->autres;
    }

    public function setAutres(float $autres): self
    {
        $this->autres = $autres;

        return $this;
    }

    public function getAbscence(): ?float
    {
        return $this->abscence;
    }

    public function setAbscence(?float $abscence): self
    {
        $this->abscence = $abscence;

        return $this;
    }

    /**
     * @return int
     */
    public function getDeductionPrecedente(): int
    {
        return $this->deductionPrecedente;
    }

    /**
     * @param int $deductionPrecedente
     */
    public function setDeductionPrecedente(int $deductionPrecedente): void
    {
        $this->deductionPrecedente = $deductionPrecedente;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function calculBrutImposable() : ?float
    {
        $remunerations = array($this->base, $this->primeDiplome, $this->heureSupplementaire);
        return array_sum($remunerations);
    }

    public function calculTotalIndemnite() : ?float 
    {
        $indemnites = array($this->transport, $this->logement, $this->allocationFamiliale, $this->autres);
        return  array_sum($indemnites);
    }

    public function calculSalaireBrut() : ?float
    {
        $gains = array($this->calculBrutImposable(), $this->calculTotalIndemnite());
        return array_sum($gains);
    }

    public function calculDeduction() : ?float
    {
        $deductions = array($this->cnss, $this->ipr, $this->avanceSalaire, $this->pretLogement, $this->pretFraisScolaire, 
        $this->pretDeuil, $this->pretAutre,  $this->abscence, $this->deductionPrecedente);

        return array_sum($deductions);
    }

    public function calculNetAPayer(): ?float
    {

        return $this->calculSalaireBrut() - $this->calculDeduction();
    }

}
