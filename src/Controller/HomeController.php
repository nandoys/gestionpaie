<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Paiement;
use App\Entity\Indemnite;
use App\Form\PaiementType;
use App\Entity\Remuneration;
use App\Form\ImportFileType;
use App\Form\AgentSalaireType;
use App\Repository\AgentRepository;
use App\Repository\PaiementRepository;
use App\Repository\IndemniteRepository;
use App\Service\ImportAgent;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RemunerationRepository;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
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
        
        return $this->render('home/fonction.twig');
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    #[Route('/agent', name: 'home_agent')]
    #[Route('/agent/{id}/update', name: 'home_agent_update')]
    public function agent(Request $request, AgentRepository $repoAgent, RemunerationRepository $repoRemuneration,
     IndemniteRepository $repoIndem,Agent $agent, PaginatorInterface $paginator, ContainerInterface $container, Xlsx $reader, Filesystem $filesystem) : Response
    {

        // check the mode to handle modal form
        $is_creating_agent = $agent->getId() === NULL;

        if(!$is_creating_agent) { 

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

        $formUpload = $this->createForm(ImportFileType::class);

        //dd($formUpload->getData());
        $form->handleRequest($request);

        $formUpload->handleRequest($request);

        if ($formUpload->isSubmitted()) {
            $file = $formUpload->getData()['fichier'];

            $import = new ImportAgent($file, $container, $reader, $filesystem);

            $import->load();

        }

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
            'form_upload' => $formUpload->createView(),
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

    #[Route('/agent/{id}/paiement/{paiement_id}/delete', name: 'home_agent_paiment_delete')]
    public function home_agent__paiment_delete( Agent $agent, Request $request, PaiementRepository $repoPaie){
        $paiement_id = $request->attributes->get('paiement_id');

        $paiement = $repoPaie->findOneBy(['id'=>$paiement_id,'agent' =>$agent]);

        if($paiement->getId() !== NULL) {
            $this->em->remove($paiement);
            $this->em->flush();
            $this->addFlash('success', "Votre suppression s'est bien effectuée.");
        }

        return $this->redirectToRoute('home_agent_liste_paiement', ['id' => $agent->getId()]);
    }

    #[Route('/agent/{id}/paie', name: 'home_agent_paiement')]
    #[Route('/agent/{id}/paie/{paiement_id}/update', name: 'home_agent_paiement_update')]
    public function agent_paiement(Agent $agent, PaginatorInterface $paginator, Request $request, PaiementRepository $repoPaie, AgentRepository $repoAgent) 
    {
        $paiement_id = $request->attributes->get('paiement_id');
        $paiement = $repoPaie->findOneBy(['id'=>$paiement_id,'agent' =>$agent]) ?? new Paiement($agent->getRemuneration(), $agent->getIndemnite(), $agent);
  
        $agents = $paginator->paginate(
            $repoAgent->findAll(),
            $request->query->getInt('page', 1),
            5 /*limit per page*/
        );

        $form_paie = $this->createForm(PaiementType::class, $paiement);
        //dd($paiement->calculSalaireBrut(), $paiement->calculDeduction(), $paiement->calculNetAPayer());


        $form_paie->handleRequest($request);

        if ($form_paie->isSubmitted() && $form_paie->isValid()) {
            
            if ($paiement->getId() === NULL ) {
                $this->em->persist($paiement);
                //$this->addFlash('success', "Vous venez d'ajouter un nouveau paiment.");
                //$this->redirectToRoute('home_agent_liste_paiement', ['id' => $agent->getId()]);
            }
            
            $this->em->flush();
        }
        
        return  $this->render('home/agent_paie.html.twig', [
            'form_paie' => $form_paie,
            'agent' => $agent,
            'agents' => $agents,
            'paiement' => $paiement,
        ]);
    }



}
