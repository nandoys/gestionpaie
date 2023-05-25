<?php

namespace App\Entity;

use App\Repository\IndemniteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IndemniteRepository::class)]
class Indemnite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $transport = null;

    #[ORM\Column]
    private ?float $logement = null;

    #[ORM\Column]
    private ?float $allocationFamiliale = null;

    #[ORM\Column]
    private ?float $autres = null;

    #[ORM\OneToOne(inversedBy: 'indemnite', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agent $agent = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function calculTotalIndemnite() 
    {
        return $this->transport + $this->logement + $this->allocationFamiliale + $this->autres;
    }
}
