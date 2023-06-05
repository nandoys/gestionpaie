<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Paiement;
use App\Form\PaiementType;
use App\Repository\AgentRepository;
use App\Repository\PaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaiementController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}


    #[Route('/agent/{id}/paiements', name: 'paiement_agent_liste')]
    public function paiement_agent_liste(Agent $agent, Request $request, PaiementRepository $repoPaie, PaginatorInterface $paginator): Response
    {
        $paiements = $repoPaie->findByAgent($agent);

        $paiements = $paginator->paginate(
            $repoPaie->findByAgent($agent),
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );

        return  $this->render('paiement/agent_liste.html.twig', [
            'agent' => $agent,
            'paiements' => $paiements
        ]);
    }

    #[Route('/agent/{id}/paiement/{paiement_id}/delete', name: 'paiement_agent_delete')]
    public function paiement_agent_delete( Agent $agent, Request $request, PaiementRepository $repoPaie): RedirectResponse
    {
        $paiement_id = $request->attributes->get('paiement_id');

        $paiement = $repoPaie->findOneBy(['id'=>$paiement_id,'agent' =>$agent]);

        if($paiement->getId() !== NULL) {
            $this->em->remove($paiement);
            $this->em->flush();
            $this->addFlash('success', "Votre suppression s'est bien effectuée.");
        }

        return $this->redirectToRoute('paiement_agent_liste', ['id' => $agent->getId()]);
    }

    #[Route('/agent/{id}/paie', name: 'paiement_agent')]
    #[Route('/agent/{id}/paie/{paiement_id}/update', name: 'paiement_agent_update')]
    public function paiement_agent(Agent $agent, PaginatorInterface $paginator, Request $request, PaiementRepository $repoPaie, AgentRepository $repoAgent): Response
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

        return  $this->render('paiement/agent_paie.html.twig', [
            'form_paie' => $form_paie,
            'agent' => $agent,
            'agents' => $agents,
            'paiement' => $paiement,
        ]);
    }

}