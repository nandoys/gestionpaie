<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AgentRepository;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: AgentRepository::class)]
#[ApiResource(
        normalizationContext: [
            'groups' => ['read:agent']
        ]
    )
]
#[
    UniqueEntity('matricule', message:"Ce numéro matricule {{ value }} existe déjà "),
    UniqueEntity('numeroCnss', message:"Ce numéro CNSS {{ value }} existe déjà ")
]
class Agent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:agent', 'read:paiements'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:agent', 'read:paiements'])]
    #[
        Assert\NotBlank(message:"Le nom est obligatoire"),
        Assert\Length(
            min:2, minMessage:"Le nom ne peut pas être moins de 2 caractères",
            max:20, maxMessage:"Le nom ne peut pas être plus de 20 caractères"
        )
    ]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:agent', 'read:paiements'])]
    #[
        Assert\NotBlank(message:"Le postnom est obligatoire"),
        Assert\Length(
            min:2, minMessage:"Le postnom ne peut pas être moins de 2 caractères", 
            max:20, maxMessage:"Le postnom ne peut pas être plus de 20 caractères"
        )
    ]
    private ?string $postnom = null;

    #[ORM\Column(length: 255)]
    #[Groups('read:agent')]
    #[
        Assert\NotBlank(message:"Le prenom est obligatoire"),
        Assert\Length(
            min:2, minMessage:"Le prenom ne peut pas être moins de 2 caractères", 
            max:20, maxMessage:"Le prenom ne peut pas être plus de 20 caractères"
        )
    ]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"La date de naissance est obligatoire")]
    #[Assert\Type(type:"\DateTime", message:"La valeur n'est pas une date au format valide")]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[
        Assert\NotBlank(message:"Le lieu de naissance est obligatoire"),
        Assert\Length(min:2, minMessage:"Le lieu de naissance ne peut pas être moins de 2 caractères")
    ]
    private ?string $lieuNaissance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[
        Assert\NotBlank(message:"La date de début de contrat est obligatoire"),
        Assert\Type(type:"\DateTime", message:"La valeur n'est pas une date au format valide"),
        Assert\LessThan(propertyPath:"finContrat")
    ]
    private ?\DateTimeInterface $debutContrat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[
        Assert\Type(type:"\DateTime", message:"La valeur n'est pas une date au format valide"),
        Assert\GreaterThan(propertyPath:"debutContrat")
    ]
    private ?\DateTimeInterface $finContrat = null;

    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank(message:"Le matricule est obligatoire"),
    ]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank(message:"Le sexe est obligatoire"),
        Assert\Choice(["Homme", "Femme"], message:"Le sexe doit être un homme ou une femme")
    ]
    private ?string $sexe = null;

    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank(message:"Le numéro CNSS est obligatoire"),
        Assert\NotNull(message:"Le numéro CNSS ne peut pas être null")
    ]
    private ?string $numeroCnss = null;

    #[ORM\Column]
    #[
        Assert\NotBlank(message:"Le nombre d'enfant est obligatoire"),
        Assert\Type(type: 'integer', message:"La valeur {{ value }} n'est pas un {{ type }} valide."),
        Assert\PositiveOrZero(message:"Le nombre d'enfant n'est pas valide, doit être égal ou supérieur à zéro")
    ]
    private ?int $nombreEnfant = null;

    #[ORM\ManyToOne(inversedBy: 'agents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fonction $fonction = null;

    #[ORM\ManyToOne(inversedBy: 'agent')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Diplome $diplome = null;

    #[ORM\ManyToOne(inversedBy: 'agent')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EtatCivil $etatCivil = null;

    #[ORM\OneToOne(mappedBy: 'agent', cascade: ['persist', 'remove'])]
    private ?Remuneration $remuneration = null;

    #[ORM\OneToOne(mappedBy: 'agent', cascade: ['persist', 'remove'])]
    private ?Indemnite $indemnite = null;

    #[ORM\OneToMany(mappedBy: 'agent', targetEntity: Paiement::class, orphanRemoval: true)]
    private Collection $paiements;

    #[ORM\OneToMany(mappedBy: 'agent', targetEntity: PretAgent::class, orphanRemoval: true)]
    private Collection $pretsAgent;

    #[ORM\OneToMany(mappedBy: 'agent', targetEntity: AvanceSalaire::class, orphanRemoval: true)]
    private Collection $avanceSalaires;

    public function __construct()
    {
        $this->paiements = new ArrayCollection();
        $this->pretAgents = new ArrayCollection();
        $this->avanceSalaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFamille(){
        return "{$this->nom} {$this->postnom}";
    }

    public function getNomComplet(){
        return "{$this->nom} {$this->postnom} {$this->prenom}";
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPostnom(): ?string
    {
        return $this->postnom;
    }

    public function setPostnom(string $postnom): self
    {
        $this->postnom = $postnom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getLieuNaissance(): ?string
    {
        return $this->lieuNaissance;
    }

    public function setLieuNaissance(?string $lieuNaissance): self
    {
        $this->lieuNaissance = $lieuNaissance;

        return $this;
    }

    public function getDebutContrat(): ?\DateTimeInterface
    {
        return $this->debutContrat;
    }

    public function setDebutContrat(\DateTimeInterface $debutContrat): self
    {
        $this->debutContrat = $debutContrat;

        return $this;
    }

    public function getFinContrat(): ?\DateTimeInterface
    {
        return $this->finContrat;
    }

    public function setFinContrat(?\DateTimeInterface $finContrat): self
    {
        $this->finContrat = $finContrat;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getNumeroCnss(): ?string
    {
        return $this->numeroCnss;
    }

    public function setNumeroCnss(string $numeroCnss): self
    {
        $this->numeroCnss = $numeroCnss;

        return $this;
    }

    public function getNombreEnfant(): ?int
    {
        return $this->nombreEnfant;
    }

    public function setNombreEnfant(int $nombreEnfant): self
    {
        $this->nombreEnfant = $nombreEnfant;

        return $this;
    }

    public function getFonction(): ?Fonction
    {
        return $this->fonction;
    }

    public function setFonction(?Fonction $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getDiplome(): ?Diplome
    {
        return $this->diplome;
    }

    public function setDiplome(?Diplome $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getEtatCivil(): ?EtatCivil
    {
        return $this->etatCivil;
    }

    public function setEtatCivil(?EtatCivil $etatCivil): self
    {
        $this->etatCivil = $etatCivil;

        return $this;
    }

    public function getRemuneration(): ?Remuneration
    {
        return $this->remuneration;
    }

    public function setRemuneration(Remuneration $remuneration): self
    {
        // set the owning side of the relation if necessary
        if ($remuneration->getAgent() !== $this) {
            $remuneration->setAgent($this);
        }

        $this->remuneration = $remuneration;

        return $this;
    }

    public function getIndemnite(): ?Indemnite
    {
        return $this->indemnite;
    }

    public function setIndemnite(Indemnite $indemnite): self
    {
        // set the owning side of the relation if necessary
        if ($indemnite->getAgent() !== $this) {
            $indemnite->setAgent($this);
        }

        $this->indemnite = $indemnite;

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
            $paiement->setAgent($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): self
    {
        if ($this->paiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getAgent() === $this) {
                $paiement->setAgent(null);
            }
        }

        return $this;
    }

    public function typeContrat() : ?string
    {
        if ($this->finContrat === NULL) {
            return "CDI";
        } else {
            return "CDD";
        }
    }

    public function statusContrat() : ?string
    {
        if ($this->finContrat !== NULL && $this->finContrat < new \DateTime('today')) {
            return "terminé";
        } else {
            return "en cours";
        }
    }

    /**
     * @return Collection<int, PretAgent>
     */
    public function getPretAgents(): Collection
    {
        return $this->pretAgents;
    }

    public function addPretAgent(PretAgent $pretAgent): self
    {
        if (!$this->pretsAgent->contains($pretAgent)) {
            $this->pretsAgent->add($pretAgent);
            $pretAgent->setAgent($this);
        }

        return $this;
    }

    public function removePretAgent(PretAgent $pretAgent): self
    {
        if ($this->pretsAgent->removeElement($pretAgent)) {
            // set the owning side to null (unless already changed)
            if ($pretAgent->getAgent() === $this) {
                $pretAgent->setAgent(null);
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
            $avanceSalaire->setAgent($this);
        }

        return $this;
    }

    public function removeAvanceSalaire(AvanceSalaire $avanceSalaire): self
    {
        if ($this->avanceSalaires->removeElement($avanceSalaire)) {
            // set the owning side to null (unless already changed)
            if ($avanceSalaire->getAgent() === $this) {
                $avanceSalaire->setAgent(null);
            }
        }

        return $this;
    }

}
