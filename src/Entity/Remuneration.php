<?php

namespace App\Entity;

use App\Repository\RemunerationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RemunerationRepository::class)]
class Remuneration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $base = null;

    #[ORM\Column]
    private ?float $primeDiplome = null;

    #[ORM\Column]
    private ?float $heureSupplementaire = null;

    #[ORM\OneToOne(inversedBy: 'remuneration', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agent $agent = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function calculBrutImposable()
    {
        return $this->base + $this->primeDiplome + $this->heureSupplementaire;
    }
}
