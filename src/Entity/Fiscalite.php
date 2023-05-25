<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FiscaliteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FiscaliteRepository::class)]
#[ApiResource]
class Fiscalite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $ipr = null;

    #[ORM\Column]
    private ?float $iere = null;

    #[ORM\Column]
    private ?bool $is_local = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIere(): ?float
    {
        return $this->iere;
    }

    public function setIere(float $iere): self
    {
        $this->iere = $iere;

        return $this;
    }

    public function isIsLocal(): ?bool
    {
        return $this->is_local;
    }

    public function setIsLocal(bool $is_local): self
    {
        $this->is_local = $is_local;

        return $this;
    }
}
