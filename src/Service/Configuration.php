<?php

namespace App\Service;

use App\Entity\Diplome;
use App\Entity\EtatCivil;
use App\Entity\Fonction;
use App\Entity\User;
use App\Repository\DiplomeRepository;
use App\Repository\EtatCivilRepository;
use App\Repository\FonctionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Configuration
{
    private $etatsCivil = ["Celibataire", "Marié(e)", "Divorcé(e)", "Veuf(ve)", "Union de fait"];
    private $diplomes = ["Sans diplôme", "D4", "D6", "G3", "L2", "M2", "PHD"];

    private $fonctions = ["Educatrice", "Enseignant", "EPRO", "DP", "DM", "Surnumeraire", "Surveillant", "Sentinelle", "Préfet"];

    public function __construct(private UserPasswordHasherInterface $hasher, private EntityManagerInterface $em, private UserRepository $repoUser,
    private EtatCivilRepository $repoEtatCivil, private DiplomeRepository $repoDiplome, private FonctionRepository $repoFonction)
    {
    }

    public function createIfNoUser() {
        if (count($this->repoUser->findAll()) == 0) {
            $user = new User();

            $user->setUsername('Gamaliel')
                ->setPassword($this->hasher->hashPassword($user, '1234'));
            $this->em->persist($user);
            $this->em->flush();
        }
    }
    public function createIfNoMaritalStatus() {
        if (count($this->repoEtatCivil->findAll()) != count($this->etatsCivil)) {

            foreach ($this->etatsCivil as $statut) {
                $etatCivilExiste = $this->repoEtatCivil->findOneBy(['titre' => $statut]);

                if (!$etatCivilExiste) {
                    $etatCivil = new EtatCivil();
                    $etatCivil->setTitre($statut);
                    $this->em->persist($etatCivil);
                }
            }

            $this->em->flush();
        }
    }
    public function createIfNoDiplome() {
        if (count($this->repoDiplome->findAll()) != count($this->diplomes)) {

            foreach ($this->diplomes as $diplome) {
                $diplomeExiste = $this->repoDiplome->findOneBy(['titre' => $diplome]);

                if (!$diplomeExiste) {
                    $niveau = new Diplome();
                    $niveau->setTitre($diplome);
                    $this->em->persist($niveau);
                }
            }

            $this->em->flush();
        }
    }
    public function createIfNoFonction() {
        if (count($this->repoFonction->findAll()) != count($this->fonctions)) {

            foreach ($this->fonctions as $fonction) {
                $fonctionExiste = $this->repoFonction->findOneBy(['titre' => $fonction]);

                if (!$fonctionExiste) {
                    $poste = new Fonction();
                    $poste->setTitre($fonction);
                    $poste->setBaseSalarial(200_000);
                    $this->em->persist($poste);
                }
            }

            $this->em->flush();
        }
    }



}