<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\FonctionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

#[ORM\Entity(repositoryClass: FonctionRepository::class)]
#[ApiResource]
#[UniqueEntity('titre', message:"Cette fonction existe déjà")]
class Fonction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[
        ORM\Column(length: 255),
        NotBlank(message: "Le titre est obligatoire"),
        Length(min: 2, minMessage: "Le titre doit être d'au moins 2 caractères")
    ]
    private ?string $titre = null;

    #[ORM\OneToMany(mappedBy: 'fonction', targetEntity: Agent::class)]
    private Collection $agents;

    #[
        ORM\Column,
        NotBlank(message: "La base salariale est obligatoire"),
        Positive(message: "Le salaire doit être un nombre positif supérieur à zéro")
    ]
    private ?float $baseSalarial = null;

    public function __construct()
    {
        $this->agents = new ArrayCollection();
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
    public function getAgents(): Collection
    {
        return $this->agents;
    }

    public function addAgent(Agent $agent): self
    {
        if (!$this->agents->contains($agent)) {
            $this->agents->add($agent);
            $agent->setFonction($this);
        }

        return $this;
    }

    public function removeAgent(Agent $agent): self
    {
        if ($this->agents->removeElement($agent)) {
            // set the owning side to null (unless already changed)
            if ($agent->getFonction() === $this) {
                $agent->setFonction(null);
            }
        }

        return $this;
    }

    public function getBaseSalarial(): ?float
    {
        return $this->baseSalarial;
    }

    public function setBaseSalarial(float $baseSalarial): self
    {
        $this->baseSalarial = $baseSalarial;

        return $this;
    }
}
