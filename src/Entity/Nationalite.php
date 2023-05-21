<?php

namespace App\Entity;

use App\Repository\NationaliteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NationaliteRepository::class)]
class Nationalite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\OneToMany(mappedBy: 'nationalite', targetEntity: Agent::class)]
    private Collection $agent;

    #[ORM\Column]
    private ?bool $is_local = null;

    public function __construct()
    {
        $this->agent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return Collection<int, Agent>
     */
    public function getAgent(): Collection
    {
        return $this->agent;
    }

    public function addAgent(Agent $agent): self
    {
        if (!$this->agent->contains($agent)) {
            $this->agent->add($agent);
            $agent->setNationalite($this);
        }

        return $this;
    }

    public function removeAgent(Agent $agent): self
    {
        if ($this->agent->removeElement($agent)) {
            // set the owning side to null (unless already changed)
            if ($agent->getNationalite() === $this) {
                $agent->setNationalite(null);
            }
        }

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
