<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\AvanceSalaire;
use App\Entity\Paiement;
use App\Entity\PretAgent;
use App\Form\AvanceSalaireType;
use App\Form\ImportFileType;
use App\Form\PaiementType;
use App\Form\PretAgentType;
use App\Repository\AgentRepository;
use App\Repository\AvanceSalaireRepository;
use App\Repository\ExerciceRepository;
use App\Repository\PaiementRepository;
use App\Repository\PretAgentRepository;
use App\Service\DateRange;
use App\Service\DenormaliseurPaie;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaiementController extends AbstractController
{
    private EntityManagerInterface $em;

    private ExerciceRepository $repoExercice;
    private PaiementRepository $repoPaie;

    private AvanceSalaireRepository $repoAvance;

    private PretAgentRepository $repoPret;

    private PaginatorInterface $paginator;
    public function __construct(EntityManagerInterface $em, ExerciceRepository $repoExercice, PaiementRepository $repoPaie,
                                PaginatorInterface $paginator, AvanceSalaireRepository $repoAvance, PretAgentRepository $repoPret)
    {
        $this->em = $em;
        $this->repoExercice = $repoExercice;
        $this->repoPaie = $repoPaie;
        $this->repoAvance = $repoAvance;
        $this->repoPret = $repoPret;
        $this->paginator = $paginator;
    }


    #[Route('/agent/{id}/paiements', name: 'paiement_agent_liste')]
    #[Route('/agent/{id}/paiements/avance/{avance_id}/update', name: 'paiement_avance_salaire_agent_update')]
    #[Route('/agent/{id}/paiements/pret/{pret_id}/update', name: 'paiement_pret_agent_update')]
    public function paiement_agent_liste(Agent $agent, Request $request, AvanceSalaireRepository $repoAvance): Response
    {
        $exercice = $this->repoExercice->findOneByEstCloture(false);

        $paiements = $this->repoPaie->findByAgent($agent);

        // concerne la partie avance salaire
        $avancesSalaire = $this->paginator->paginate(
            $repoAvance->findBy(['agent' => $agent, 'exercice' => $exercice], ['dateAt' => 'DESC']),
            $request->query->getInt('page-avance-salaire', 1),
            15 /*limit per page*/
        );
        $avancesSalaire->setPaginatorOptions(['pageParameterName' => 'page-avance-salaire']);

        $avance_id = $request->attributes->get('avance_id');

        if ($avance_id !== NULL) {
            $avanceSalaire = $this->repoAvance->findOneBy(['id'=>$avance_id,'agent' =>$agent]);
        } else {
            $avanceSalaire = new AvanceSalaire();
            $avanceSalaire->setAgent($agent);
            $avanceSalaire->setExercice($exercice);
        }

        $is_creating_avance = $avanceSalaire->getId() === NULL;

        $formAvanceSalaire = $this->createForm(AvanceSalaireType::class, $avanceSalaire);

        $formAvanceSalaire->handleRequest($request);

        if ($formAvanceSalaire->isSubmitted() && $formAvanceSalaire->isValid()) {

            if ($avanceSalaire->getId() === NULL ) {
                $this->em->persist($avanceSalaire);
                $this->addFlash('success-avance', "Vous venez d'ajouter une nouvelle avance sur salaire pour l'agent {$agent->getNomComplet()} 
                (Matricule: {$agent->getMatricule()}) d'un montant de {$avanceSalaire->getMontant()} FC en date du {$avanceSalaire->getDateAt()->format('d/m/Y')}");
            }
            else {
                $this->addFlash('success-avance', "Vos modifications sur l'avance sur salaire de l'agent {$agent->getNomComplet()} (Matricule: {$agent->getMatricule()}) ont été enregistrées");
            }
            $this->em->flush($avanceSalaire);

            return $this->redirectToRoute('paiement_agent_liste', [
                'id' => $agent->getId(), 'page-avance-salaire' => 1
            ]);
        }


        // concerne la partie de prêt
        $prets = $this->paginator->paginate(
            $this->repoPret->findBy(['agent' => $agent, 'exercice' => $exercice], ['dateAt' => 'DESC']),
            $request->query->getInt('page-pret', 1),
            15 /*limit per page*/
        );

        $prets->setPaginatorOptions(['pageParameterName' => 'page-pret']);

        $pret_id = $request->attributes->get('pret_id');

        if ($pret_id !== NULL) {
            $pretAgent = $this->repoPret->findOneBy(['id'=>$pret_id,'agent' =>$agent]);
        } else {
            $pretAgent = new PretAgent();
            $pretAgent->setAgent($agent);
            $pretAgent->setExercice($exercice);
        }

        $is_creating_pret = $pretAgent->getId() === NULL;

        $formPretAgent = $this->createForm(PretAgentType::class, $pretAgent);

        $formPretAgent->handleRequest($request);

        if ($formPretAgent->isSubmitted() && $formPretAgent->isValid()) {

            if ($pretAgent->getId() === NULL ) {
                $this->em->persist($pretAgent);
                $this->addFlash('success-pret', "Vous venez d'ajouter un prêt ". strtolower($pretAgent->getTypePret()) ." pour l'agent {$agent->getNomComplet()} 
                (Matricule: {$agent->getMatricule()}) d'un montant de {$pretAgent->getMontant()} FC en date du {$pretAgent->getDateAt()->format('d/m/Y')}");
            }
            else {
                $this->addFlash('success-pret', "Vos modifications sur le prêt ". strtolower($pretAgent->getTypePret()) ." de l'agent {$agent->getNomComplet()} (Matricule: {$agent->getMatricule()}) ont été enregistrées");
            }
            $this->em->flush($pretAgent);

            return $this->redirectToRoute('paiement_agent_liste', [
                'id' => $agent->getId(), 'page-pret' => 1
            ]);
        }

        $formUpload = $this->createForm(ImportFileType::class);

        $paiements = $this->paginator->paginate(
            $this->repoPaie->findByAgent($agent, ['dateAt' => 'DESC']),
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );

        return  $this->render('paiement/agent_liste_paie.twig', [
            'agent' => $agent,
            'paiements' => $paiements,
            'avancesSalaire' => $avancesSalaire,
            'form_avance' => $formAvanceSalaire,
            'is_creating_avance' => $is_creating_avance,
            'prets' => $prets,
            'form_pret' => $formPretAgent,
            'is_creating_pret' => $is_creating_pret,
            'form_upload' => $formUpload
        ]);
    }

    #[Route('/agent/{id}/paiement/{paiement_id}/delete', name: 'paiement_agent_delete')]
    public function paiement_agent_delete(Agent $agent, Request $request): RedirectResponse
    {
        $paiement_id = $request->attributes->get('paiement_id');

        $paiement = $this->repoPaie->findOneBy(['id'=>$paiement_id,'agent' =>$agent]);

        if($paiement->getId() !== NULL) {
            $this->em->remove($paiement);

            foreach ($paiement->getAvances()->toArray() as $avance) {
                $avance->setEstCloture(false);
            }

            foreach ($paiement->getPrets()->toArray() as $pret) {
                $pret->setEstCloture(false);
            }

            $this->em->flush();
            $this->addFlash('success', "Votre suppression s'est bien effectuée.");
        }

        return $this->redirectToRoute('paiement_agent_liste', ['id' => $agent->getId()]);
    }

    #[Route('/agent/{id}/avance-salaire/{avance_id}/delete', name: 'paiement_avance_salaire_agent_delete')]
    public function paiement_avance_salaire_agent_delete(Agent $agent, Request $request) : RedirectResponse
    {
        $avance_id = $request->attributes->get('avance_id');

        $avance = $this->repoAvance->findOneBy(['id'=>$avance_id,'agent' =>$agent]);

        if($avance->getId() !== NULL) {
            $this->em->remove($avance);
            $this->em->flush();
            $this->addFlash('success-avance', "La suppresion de l'avance salaire s'est bien effectuée.");
        }

        return $this->redirectToRoute('paiement_agent_liste', ['id' => $agent->getId(), 'page-avance-salaire' => 1]);
    }

    #[Route('/agent/{id}/pret/{pret_id}/delete', name: 'paiement_pret_agent_delete')]
    public function paiement_pret_agent_delete(Agent $agent, Request $request) : RedirectResponse
    {
        $pret_id = $request->attributes->get('pret_id');

        $pret = $this->repoPret->findOneBy(['id'=>$pret_id,'agent' =>$agent]);

        if($pret->getId() !== NULL) {
            $this->em->remove($pret);
            $this->em->flush();
            $this->addFlash('success-pret', "La suppresion du prêt s'est bien effectuée.");
        }

        return $this->redirectToRoute('paiement_agent_liste', ['id' => $agent->getId(), 'page-pret' => 1]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/agent/{id}/paie', name: 'paiement_agent')]
    #[Route('/agent/{id}/paie/{paiement_id}/update', name: 'paiement_agent_update')]
    public function paiement_agent(Agent $agent, Request $request, AgentRepository $repoAgent): Response
    {
        if (count($this->repoExercice->findAll()) == 0) {
            $this->addFlash('warning', "Vous devez avoir un exercice avant d'accéder au module de paie! Veuillez en créer un par ici");
            return $this->redirectToRoute('app_configuration_exercice');
        }

        $exercice = $this->repoExercice->findOneByEstCloture(false);

        $paiement_id = $request->attributes->get('paiement_id');
        $paiement = $this->repoPaie->findOneBy(['id'=>$paiement_id,'agent' =>$agent]) ??
            new Paiement($agent->getRemuneration(), $agent->getIndemnite(), $agent, $this->em);

        if ($paiement->getId() !== NULL ) {
            $paiement->setEntityManager($this->em);
        }

        $agents = $this->paginator->paginate(
            $repoAgent->findAll(),
            $request->query->getInt('page', 1),
            5 /*limit per page*/
        );

        $form_paie = $this->createForm(PaiementType::class, $paiement, [
            'min' =>  $exercice->getDebutAnnee()->format('d/m/Y'),
            'max' =>  $exercice->getFinAnnee()->format('d/m/Y'),
        ]);

        $form_paie->handleRequest($request);

        if ($form_paie->isSubmitted() && $form_paie->isValid()) {
            
            if(!$exercice->valide($paiement->getDateAt())) {
                $this->addFlash('danger', "La date du paiement est incorrecte.");
                if ($paiement->getId() !== NULL ) {
                    return $this->redirectToRoute('paiement_agent_update', ['id' => $agent->getId(), 'paiement_id' => $paiement->getId()]);
                } else {
                    return $this->redirectToRoute('paiement_agent', ['id' => $agent->getId()]);
                }
            }
           
            $avance = $this->repoAvance->findUnpaidAvanceSalaire($paiement->getAgent(), $paiement->getDateAt());

            if ($avance !== NULL) {
                foreach ($avance as $key => $value) {
                    $value->cloturer();
                    $paiement->addAvance($value);
                }
            }

            $pretLogement = $this->repoPret->findFirstUnpaidPret($paiement->getAgent(), 'Logement', $paiement->getDateAt());

            if (count($pretLogement) > 0) {
                
                foreach ($pretLogement as $pret) {
                    $pret->cloturer();
                    $paiement->addPret($pret);
                }
            } else {
                $paiement->setPretLogement(0);
            }

            $pretFraisScolaire = $this->repoPret->findFirstUnpaidPret($paiement->getAgent(), 'Frais scolaire', $paiement->getDateAt());

            if (count($pretFraisScolaire) > 0) {
                foreach ($pretFraisScolaire as $pret) {
                    $pret->cloturer();
                    $paiement->addPret($pret);
                }
            } else {
                $paiement->setPretFraisScolaire(0);
            }

            $pretDeuil = $this->repoPret->findFirstUnpaidPret($paiement->getAgent(), 'Deuil', $paiement->getDateAt());

            if (count($pretDeuil) > 0) {
                foreach ($pretDeuil as $pret) {
                    $pret->cloturer();
                    $paiement->addPret($pret);
                }
            } else {
                $paiement->setPretDeuil(0);
            }

            $pretAutres = $this->repoPret->findFirstUnpaidPret($paiement->getAgent(), 'Autres', $paiement->getDateAt());

            if (count($pretAutres) > 0) {
                foreach ($pretAutres as $pret) {
                    $pret->cloturer();
                    $paiement->addPret($pret);
                }
            } else {
                $paiement->setPretAutre(0);
            }

            if ($paiement->getId() === NULL ) {
                $this->em->persist($paiement);
                $this->addFlash('success', "Vous venez d'ajouter un nouveau paiement.");
            } else {
                $this->addFlash('success', "Votre modification du paiement s'est bien éffectuée.");
            }

            $this->em->flush();
            return $this->redirectToRoute('paiement_agent_liste', ['id' => $agent->getId()]);
        }

        return  $this->render('paiement/agent_paie.html.twig', [
            'form_paie' => $form_paie,
            'agent' => $agent,
            'agents' => $agents,
            'paiement' => $paiement,
        ]);
    }

    #[Route('/bulletin', name: 'bulletin_paie_index')]
    public function bulletin_paie_index(DateRange $dateRange, AgentRepository $repoAgent, Request $request): Response
    {
        if (count($this->repoExercice->findAll()) == 0) {
            $this->addFlash('warning', "Vous devez avoir un exercice avant d'accéder au bulletin de paie! Veuillez créer un nouveau exercice comptable");
            return $this->redirectToRoute('app_configuration_exercice');
        }

        $exercice = $this->repoExercice->findOneByEstCloture(false);
        $periodes = $dateRange->getMonthsInRange($exercice->getDebutAnnee(), $exercice->getFinAnnee());

        $bulletins = [];

        foreach ($periodes as $periode) {
            $data = $this->repoPaie->findAllPaymentBill($periode['mois'], $periode['annee']);

            $data_denormalized = new DenormaliseurPaie($data, $repoAgent, $this->em);

            $paginator = $this->paginator->paginate(
                $data_denormalized->getDenormalizedData(),
                $request->query->getInt('page-periode-'.$periode['mois'].$periode['annee'], 1),
                10);

            $paginator->setPaginatorOptions(['pageParameterName' => 'page-periode-'.$periode['mois'].$periode['annee'] ]);

            $bulletins[] = [
                'mois' => $periode['mois'],
                'annee' => $periode['annee'],
                'paies' => $paginator
            ];
        }

        return $this->render('bulletin/index.twig', [
            'bulletins' => $bulletins
        ]);
    }

    #[Route('/bulletin/mois/{mois}/annee/{annee}/agent/{id}', name: 'bulletin_paie_agent')]
    public function bulletin_paie_agent($mois, $annee, Agent $agent, AgentRepository $repoAgent): Response
    {
        if (count($this->repoExercice->findAll()) == 0) {
            $this->addFlash('warning', "Vous devez avoir un exercice avant d'accéder au bulletin de paie! Veuillez créer un nouveau exercice comptable");
            return $this->redirectToRoute('app_configuration_exercice');
        }

        $bulletin = $this->repoPaie->findPaymentBill($mois, $annee, $agent);

        $bulletin = new DenormaliseurPaie($bulletin, $repoAgent, $this->em);

        $generator = new BarcodeGeneratorHTML();
        $codebar = $generator->getBarcode($agent->getMatricule(), $generator::TYPE_CODE_128);
        return $this->render('paiement/bulletin_paie.twig', [
            'codebar' =>  $codebar,
            'mois' =>  $mois,
            'agent' => $agent,
            'bulletin' => $bulletin->getDenormalizedData()[0]
        ]);
    }
}