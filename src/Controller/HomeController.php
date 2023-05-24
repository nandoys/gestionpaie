<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Form\AgentType;
use App\Entity\Indemnite;
use App\Entity\Remuneration;
use App\Form\AgentSalaireType;
use App\Repository\AgentRepository;
use App\Repository\IndemniteRepository;
use App\Repository\RemunerationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em =  $em;
    }

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

            $remuneration = $repoRemuneration->findOneBy(['agent' => $agent]);

            $indemnite = $repoIndem->findOneBy(['agent' => $agent]);
           
        } else {
            $remuneration = new Remuneration();

            $indemnite = new Indemnite();
        }

        
        // get all agents
        //$agents = $repo->findAll();

        $agents = $paginator->paginate(
            $repoAgent->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
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
}
