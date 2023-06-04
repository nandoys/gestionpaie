<?php

namespace App\Entity;

use App\Repository\PretAgentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PretAgentRepository::class)]
class PretAgent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $mensualite = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $mensualitePaye = null;

    #[ORM\ManyToOne(inversedBy: 'pretAgents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agent $agent = null;

    #[ORM\ManyToOne(inversedBy: 'pretsAgents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exercice $exercice = null;

    #[ORM\Column]
    private ?bool $estCloture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getMensualite(): ?int
    {
        return $this->mensualite;
    }

    public function setMensualite(int $mensualite): self
    {
        $this->mensualite = $mensualite;

        return $this;
    }

    public function getMensualitePaye(): ?int
    {
        return $this->mensualitePaye;
    }

    public function setMensualitePaye(?int $mensualitePaye): self
    {
        $this->mensualitePaye = $mensualitePaye;

        return $this;
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

    public function getExercice(): ?Exercice
    {
        return $this->exercice;
    }

    public function setExercice(?Exercice $exercice): self
    {
        $this->exercice = $exercice;

        return $this;
    }

    public function isEstCloture(): ?bool
    {
        return $this->estCloture;
    }

    public function setEstCloture(bool $estCloture): self
    {
        $this->estCloture = $estCloture;

        return $this;
    }
}
