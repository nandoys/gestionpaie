<?php

namespace App\Entity;

use App\Repository\AgentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgentRepository::class)]
class Agent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $postnom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieuNaissance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $debutContrat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $finContrat = null;

    #[ORM\Column(length: 255)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    private ?string $sexe = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroCnss = null;

    #[ORM\Column]
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

    #[ORM\ManyToOne(inversedBy: 'agent')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Nationalite $nationalite = null;

    #[ORM\OneToOne(mappedBy: 'agent', cascade: ['persist', 'remove'])]
    private ?Remuneration $remuneration = null;

    #[ORM\OneToOne(mappedBy: 'agent', cascade: ['persist', 'remove'])]
    private ?Indemnite $indemnite = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNationalite(): ?Nationalite
    {
        return $this->nationalite;
    }

    public function setNationalite(?Nationalite $nationalite): self
    {
        $this->nationalite = $nationalite;

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
}
