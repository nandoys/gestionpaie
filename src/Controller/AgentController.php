<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Indemnite;
use App\Entity\Remuneration;
use App\Form\ImportFileType;
use App\Form\AgentSalaireType;
use App\Repository\AgentRepository;
use App\Repository\IndemniteRepository;
use App\Service\ImportAgent;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RemunerationRepository;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AgentController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em) {}

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/', name: 'home_index')]
    public function index(MailerInterface $mailer): Response
    {
        $email = new Email();

        $email->from("trustholding.drc@gmail.com")
            ->to('grnandoy@gmail.com')
            ->subject('hello symfony')
            ->html('<p>some</p>');

        $mailer->send($email);
        return $this->redirectToRoute('agent_liste');
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    #[Route('/agent', name: 'agent_liste')]
    #[Route('/agent/{id}/update', name: 'agent_update')]
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
        
            return $this->redirectToRoute('agent_liste');
        }

        return  $this->render('agent/agent.html.twig', [
            'form_agent_salaire'=>$form->createView(),
            'form_upload' => $formUpload->createView(),
            'agents' => $agents,
            'is_creating_agent' => $is_creating_agent,
        ]);
    }

    #[Route('/agent/{id}/delete', name: 'agent_delete')]
    public function agent_delete( Agent $agent): RedirectResponse
    {
        if($agent->getId() !== NULL) {
            $this->em->remove($agent);
            $this->em->flush();
        }

        return $this->redirectToRoute('agent_liste');
    }

}
