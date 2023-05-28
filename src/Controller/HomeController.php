<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Form\AgentType;
use App\Entity\Paiement;
use App\Entity\Indemnite;
use App\Form\PaiementType;
use App\Entity\Remuneration;
use App\Form\AgentSalaireType;
use App\Repository\AgentRepository;
use App\Repository\IndemniteRepository;
use App\Repository\PaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RemunerationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em) {}

    #[Route('/', name: 'home_index')]
    public function index(): Response
    {
        
        return $this->render('home/index.html.twig');
    }

    #[Route('/agent', name: 'home_agent')]
    #[Route('/agent/{id}/update', name: 'home_agent_update')]
    public function agent(Request $request, AgentRepository $repoAgent, RemunerationRepository $repoRemuneration,
     IndemniteRepository $repoIndem,Agent $agent, PaginatorInterface $paginator) {

        // check the mode to handle modal form
        $is_creating_agent = true;

        if($agent->getId() !== NULL) { 
            $is_creating_agent = false;

            if ($agent->getRemuneration() === NULL) {
                # code...
                $remuneration = new Remuneration();
            } else {
                $remuneration = $repoRemuneration->findOneBy(['agent' => $agent]);
            }

            if ($agent->getIndemnite() === NULL) {
                # code ..
                $indemnite = new Indemnite();
            } else {
                $indemnite = $repoIndem->findOneBy(['agent' => $agent]);
            }
           
        } else {
            $remuneration = new Remuneration();

            $indemnite = new Indemnite();
        }

        
        // get all agents
        //$agents = $repo->findAll();

        $agents = $paginator->paginate(
            $repoAgent->findAll(), 
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );
    

        $form = $this->createForm(AgentSalaireType::class, [
            'agent'=>$agent,
            'remuneration'=>$remuneration,
            'indemnite'=>$indemnite
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($is_creating_agent) { 
                
                $agent->setRemuneration($remuneration);
                $agent->setIndemnite($indemnite);
                $this->em->persist($agent);

                $this->addFlash('success', "Vous venez d'ajouter un nouvel agent {$agent->getNomComplet()} (Matricule: {$agent->getMatricule()})"); 
            } else {
                if ($agent->getRemuneration() === NULL) {
                    # code...
                    $agent->setRemuneration($remuneration);
                } 
    
                if ($agent->getIndemnite() === NULL) {
                    # code ..
                    $agent->setIndemnite($indemnite);
                }

                $this->addFlash('success', "Vos modifications sur l'agent {$agent->getNomComplet()} (Matricule: {$agent->getMatricule()}) ont été enregistrées");
            }
           
            $this->em->flush();
        
            return $this->redirectToRoute('home_agent');
        }

        return  $this->render('home/agent.html.twig', [
            'form_agent_salaire'=>$form->createView(),
            'agents' => $agents,
            'is_creating_agent' => $is_creating_agent,
        ]);
    }

    #[Route('/agent/{id}/delete', name: 'home_agent_delete')]
    public function agent_delete( Agent $agent){
        if($agent->getId() !== NULL) {
            $this->em->remove($agent);
            $this->em->flush();
        }

        return $this->redirectToRoute('home_agent');
    }

    #[Route('/agent/{id}/paiements', name: 'home_agent_liste_paiement')]
    public function agent_liste_paiement(Agent $agent, Request $request, PaiementRepository $repoPaie, PaginatorInterface $paginator)
    {
        $paiements = $repoPaie->findByAgent($agent);

        $paiements = $paginator->paginate(
            $repoPaie->findByAgent($agent),
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );

        return  $this->render('home/agent_liste_paiement.html.twig', [
            'agent' => $agent,
            'paiements' => $paiements
        ]);
    }

    #[Route('/agent/{id}/paie', name: 'home_agent_paiement')]
    #[Route('/agent/{id}/paie/{paiement_id}', name: 'home_agent_paiement_update')]
    public function agent_paiement(Agent $agent, Request $request, PaiementRepository $repoPaie) 
    {
        $paiement_id = $request->attributes->get('paiement_id');
        $paiement = $repoPaie->findOneBy(['id'=>$paiement_id,'agent' =>$agent]) ?? new Paiement($agent->getRemuneration(), $agent->getIndemnite(), $agent);
  
        $form_paie = $this->createForm(PaiementType::class, $paiement);

        $form_paie->handleRequest($request);

        if ($form_paie->isSubmitted() && $form_paie->isValid()) {
            
            if ($paiement->getId() === NULL ) {
                $this->em->persist($paiement);
            }
            
            $this->em->flush();
        }
        
        return  $this->render('home/agent_paie.html.twig', [
            'form_paie' => $form_paie,
            'agent' => $agent,
            'paiement' => $paiement,
        ]);
    }

}
