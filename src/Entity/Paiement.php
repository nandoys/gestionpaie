<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
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
}
