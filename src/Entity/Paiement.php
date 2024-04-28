<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use App\Repository\AvanceSalaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PaiementRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' =>['read:paiements']],
        ),
    ]
)]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $cnss;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $ipr;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $avanceSalaire = null;

    #[ORM\Column(length: 255)]
    #[Groups('read:paiements')]
    private ?float $pretLogement = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $pretFraisScolaire = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $pretDeuil = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $pretAutre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups('read:paiements')]
    private ?\DateTimeInterface $dateAt = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $base = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $primeDiplome = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $heureSupplementaire = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $transport = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $logement = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $allocationFamiliale = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $autres = null;

    #[ORM\Column]
    #[Groups('read:paiements')]
    private ?float $exceptionnel = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('read:paiements')]
    private ?Agent $agent = null;

    #[ORM\Column(nullable: true)]
    #[Groups('read:paiements')]
    private ?float $abscence = null;

    #[ORM\ManyToMany(targetEntity: AvanceSalaire::class, mappedBy: 'paiements')]
    private Collection $avances;

    #[ORM\ManyToMany(targetEntity: PretAgent::class, mappedBy: 'paiements')]
    private Collection $prets;

    public function __construct(Remuneration $remuneration, Indemnite $indemnite, Agent $agent, private EntityManagerInterface $em)
    {
        // définir l'agent à payer
        $this->agent = $agent;
        // Données sur la rémunération
        
        $this->base = $remuneration->getBase();
        $this->primeDiplome = $remuneration->getPrimeDiplome();
        $this->heureSupplementaire = $remuneration->getHeureSupplementaire();

        // Données des indemnités
        $this->transport = $indemnite->getTransport();
        $this->logement = $indemnite->getLogement();
        $this->allocationFamiliale = $indemnite->getAllocationFamiliale();
        $this->autres = $indemnite->getAutres();
        $this->exceptionnel = $indemnite->getExceptionnel();

        // Déduction salariale
        $this->cnss = 0;
        $this->ipr = 0;
        $this->avanceSalaire = 0;
        $this->pretLogement = 0;
        $this->pretFraisScolaire = 0;
        $this->pretDeuil = 0;
        $this->pretAutre = 0;
        $this->abscence = 0;

        $this->dateAt = new \DateTime();
        $this->avances = new ArrayCollection();
        $this->prets = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCnss(): ?float
    {
        return $this->cnss;
    }

    public function setCnss(float $cnss): self
    {
        $this->cnss = $cnss;

        return $this;
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

    public function getAvanceSalaire(): ?float
    {
        return $this->avanceSalaire;
    }

    public function setAvanceSalaire(float $avanceSalaire): self
    {
        $this->avanceSalaire = $avanceSalaire;

        return $this;
    }

    public function getPretLogement(): ?float
    {
        return $this->pretLogement;
    }

    public function setPretLogement(float $pretLogement): self
    {
        $this->pretLogement = $pretLogement;

        return $this;
    }

    public function getPretFraisScolaire(): ?float
    {
        return $this->pretFraisScolaire;
    }

    public function setPretFraisScolaire(float $pretFraisScolaire): self
    {
        $this->pretFraisScolaire = $pretFraisScolaire;

        return $this;
    }

    public function getPretDeuil(): ?float
    {
        return $this->pretDeuil;
    }

    public function setPretDeuil(float $pretDeuil): self
    {
        $this->pretDeuil = $pretDeuil;

        return $this;
    }

    public function getPretAutre(): ?float
    {
        return $this->pretAutre;
    }

    public function setPretAutre(float $pretAutre): self
    {
        $this->pretAutre = $pretAutre;

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

    public function getExceptionnel(): ?float
    {
        return $this->exceptionnel;
    }

    public function setExceptionnel(float $exceptionnel): self
    {
        $this->exceptionnel = $exceptionnel;

        return $this;
    }

    public function getAbscence(): ?float
    {
        return $this->abscence;
    }

    public function setAbscence(?float $abscence): self
    {
        $this->abscence = $abscence;

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

    public function calculBrutImposable() : ?float
    {
        $remunerations = array($this->base, $this->primeDiplome, $this->heureSupplementaire);
        return array_sum($remunerations);
    }

    public function calculTotalIndemnite() : ?float 
    {
        $indemnites = array($this->transport, $this->logement, $this->allocationFamiliale, $this->autres, $this->exceptionnel);
        return  array_sum($indemnites);
    }

    public function calculSalaireBrut() : ?float
    {
        $gains = array($this->calculBrutImposable(), $this->calculTotalIndemnite());
        return array_sum($gains);
    }

    public function calculDeduction() : ?float
    {
        $deductions = array($this->cnss, $this->ipr, $this->avanceSalaire, $this->pretLogement, $this->pretFraisScolaire, 
        $this->pretDeuil, $this->pretAutre,  $this->abscence);
        
        return array_sum($deductions);
    }

    public function pretAccorde () {
        $prets = array($this->pretLogement, $this->pretDeuil, $this->pretAutre);

        return array_sum($prets);
    }

    public function calculNetAPayer(): ?float
    {

        return $this->calculSalaireBrut() - $this->calculDeduction();
    }

    /**
     * @return Collection<int, AvanceSalaire>
     */
    public function getAvances(): Collection
    {
        return $this->avances;
    }

    public function addAvance(AvanceSalaire $avance): self
    {
        if (!$this->avances->contains($avance)) {
            $this->avances->add($avance);
            $avance->addPaiement($this);
        }

        return $this;
    }

    public function removeAvance(AvanceSalaire $avance): self
    {
        if ($this->avances->removeElement($avance)) {
            $avance->removePaiement($this);
        }

        return $this;
    }

    public function getEntityManager() {
        return $this->em;
    }

    public function setEntityManager(EntityManagerInterface $em) {
         $this->em = $em;

         return $this;
    }

    /**
     * @return Collection<int, PretAgent>
     */
    public function getPrets(): Collection
    {
        return $this->prets;
    }

    public function addPret(PretAgent $pret): self
    {
        if (!$this->prets->contains($pret)) {
            $this->prets->add($pret);
            $pret->addPaiement($this);
        }

        return $this;
    }

    public function removePret(PretAgent $pret): self
    {
        if ($this->prets->removeElement($pret)) {
            $pret->removePaiement($this);
        }

        return $this;
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Assert\Callback()]
    public function validateAvance(ExecutionContextInterface $context, $payload): void
    {
        $avance = $this->em->getRepository(AvanceSalaire::class)->findUnpaidAvanceSalaire($this->agent, $this->getDateAt());
        $total_montant = 0;
        foreach ($avance as $key => $value) {
            $total_montant += $value->getMontant();
        }

    }

}
