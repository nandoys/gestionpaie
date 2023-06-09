<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AvanceSalaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AvanceSalaireRepository::class)]
#[ApiResource]
#[UniqueEntity(['dateAt', 'agent'], message: "Cet agent a déjà une avance sur salaire pour la date du {{ value }}")]
class AvanceSalaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Positive(message:"Le montant doit être supérieur à zéro")]
    private ?float $montant = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\Positive(message:"La mensulaité doit être supérieur à zéro")]
    private ?int $mensualite = null;

    #[ORM\ManyToOne(inversedBy: 'avanceSalaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agent $agent = null;

    #[ORM\ManyToOne(inversedBy: 'avanceSalaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exercice $exercice = null;

    #[ORM\Column]
    private ?bool $estCloture = false;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAt = null;

    #[ORM\ManyToMany(targetEntity: Paiement::class, inversedBy: 'avances')]
    private Collection $paiements;

    public function __construct()
    {
        $this->paiements = new ArrayCollection();


    }

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

    public function getDateAt(): ?\DateTimeInterface
    {
        return $this->dateAt;
    }

    public function setDateAt(\DateTimeInterface $dateAt): self
    {
        $this->dateAt = $dateAt;

        return $this;
    }

    /**
     * @return Collection<int, Paiement>
     */
    public function getPaiements(): Collection
    {
        return $this->paiements;
    }

    public function addPaiement(Paiement $paiement): self
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements->add($paiement);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): self
    {
        $this->paiements->removeElement($paiement);

        return $this;
    }

    public function calculDueMensuel() {
        return $this->montant / $this->mensualite;
    }

    public function cloturer() {
        if ($this->mensualite == $this->paiements->count() + 1) {
            $this->estCloture = true;
        }
        return $this;
    }
}
