<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $net = null;

    #[ORM\Column]
    private ?float $cnss = null;

    #[ORM\Column]
    private ?float $ipr = null;

    #[ORM\Column]
    private ?float $avanceSalaire = null;

    #[ORM\Column(length: 255)]
    private ?float $pretLogement = null;

    #[ORM\Column]
    private ?float $pretFraisScolaire = null;

    #[ORM\Column]
    private ?float $pretDeuil = null;

    #[ORM\Column]
    private ?float $pretAutre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datepaiement = null;

    #[ORM\Column]
    private ?float $base = null;

    #[ORM\Column]
    private ?float $primeDiplome = null;

    #[ORM\Column]
    private ?float $heureSupplementaire = null;

    #[ORM\Column]
    private ?float $transport = null;

    #[ORM\Column]
    private ?float $logement = null;

    #[ORM\Column]
    private ?float $allocationFamiliale = null;

    #[ORM\Column]
    private ?float $autres = null;

    public function __construct(Remuneration $remuneration, Indemnite $indemnite)
    {
        // Données sur la rémunération
        
        $this->base = $remuneration->getBase();
        $this->primeDiplome = $remuneration->getPrimeDiplome();
        $this->heureSupplementaire = $remuneration->getHeureSupplementaire();

        // Données des indemnités
        $this->transport = $indemnite->getTransport();
        $this->logement = $indemnite->getLogement();
        $this->allocationFamiliale = $indemnite->getAllocationFamiliale();
        $this->autres = $indemnite->getAutres();

        $this->net = $remuneration->calculBrutImposable() +  $indemnite->calculTotalIndemnite();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNet(): ?float
    {
        return $this->net;
    }

    public function setNet(float $net): self
    {
        $this->net = $net;

        return $this;
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

    public function getDatepaiement(): ?\DateTimeInterface
    {
        return $this->datepaiement;
    }

    public function setDatepaiement(\DateTimeInterface $datepaiement): self
    {
        $this->datepaiement = $datepaiement;

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
}
