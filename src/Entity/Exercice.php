<?php

namespace App\Entity;

use App\Repository\ExerciceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciceRepository::class)]
class Exercice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $debutAnnee = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $finAnnee = null;

    #[ORM\Column]
    private ?bool $estCloture = null;

    #[ORM\OneToMany(mappedBy: 'exercice', targetEntity: PretAgent::class, orphanRemoval: true)]
    private Collection $pretsAgents;

    #[ORM\OneToMany(mappedBy: 'exercice', targetEntity: AvanceSalaire::class, orphanRemoval: true)]
    private Collection $avanceSalaires;

    public function __construct()
    {

        $this->estCloture = false;
        $this->pretsAgents = new ArrayCollection();
        $this->avanceSalaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebutAnnee(): ?\DateTimeInterface
    {
        return $this->debutAnnee;
    }

    public function setDebutAnnee(\DateTimeInterface $debutAnnee): self
    {
        $this->debutAnnee = $debutAnnee;

        return $this;
    }

    public function getFinAnnee(): ?\DateTimeInterface
    {
        return $this->finAnnee;
    }

    public function setFinAnnee(\DateTimeInterface $finAnnee): self
    {
        $this->finAnnee = $finAnnee;

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

    /**
     * @return Collection<int, PretAgent>
     */
    public function getPretsAgents(): Collection
    {
        return $this->pretsAgents;
    }

    public function addPretsAgent(PretAgent $pretsAgent): self
    {
        if (!$this->pretsAgents->contains($pretsAgent)) {
            $this->pretsAgents->add($pretsAgent);
            $pretsAgent->setExercice($this);
        }

        return $this;
    }

    public function removePretsAgent(PretAgent $pretsAgent): self
    {
        if ($this->pretsAgents->removeElement($pretsAgent)) {
            // set the owning side to null (unless already changed)
            if ($pretsAgent->getExercice() === $this) {
                $pretsAgent->setExercice(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AvanceSalaire>
     */
    public function getAvanceSalaires(): Collection
    {
        return $this->avanceSalaires;
    }

    public function addAvanceSalaire(AvanceSalaire $avanceSalaire): self
    {
        if (!$this->avanceSalaires->contains($avanceSalaire)) {
            $this->avanceSalaires->add($avanceSalaire);
            $avanceSalaire->setExercice($this);
        }

        return $this;
    }

    public function removeAvanceSalaire(AvanceSalaire $avanceSalaire): self
    {
        if ($this->avanceSalaires->removeElement($avanceSalaire)) {
            // set the owning side to null (unless already changed)
            if ($avanceSalaire->getExercice() === $this) {
                $avanceSalaire->setExercice(null);
            }
        }

        return $this;
    }

}
