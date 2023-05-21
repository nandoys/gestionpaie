<?php

namespace App\Controller;

use App\Entity\Agent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/agent', name: 'home_agent')]
    public function agent() {
        $agent = new Agent();

        $form = $this->createForm(AgentType::class, $agent);

        return  $this->render('home/agent.html.twig', [
            'form_new_agent'=>$form->createView()
        ]);
    }
}
