<?php

namespace App\Controller;

use App\Entity\Fonction;
use App\Form\FonctionType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConfigurationController extends AbstractController
{
    private $em;
    private $repoFonction;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em =  $em;
        $this->repoFonction =  $em->getRepository(Fonction::class);
    }
    #[Route('/configuration', name: 'app_configuration')]
    #[Route('/configuration/fonction/{id}/update', name: 'app_configuration_fonction_update')]
    public function index(PaginatorInterface $paginator, Request $request, Fonction $fonction): Response
    {
        $page_param = $request->get('page');

        // check the mode to handle modal form
        if ( $fonction->getId() === NULL) {
            $is_creating_fonction = true;
         } else {
            $is_creating_fonction = false;
        }

        $form_fonction = $this->createForm(FonctionType::class, $fonction);

        $form_fonction->handleRequest($request);

        if ($form_fonction->isSubmitted() && $form_fonction->isvalid()) {
            
            if ( $fonction->getId() === NULL) {
                $this->em->persist($fonction);

                $this->addFlash('success', "Vous venez d'ajouter une nouvelle fonction {$fonction->getTitre()}");
            } else {
                $this->addFlash('success', "Vos modifications sur sur la fonction {$fonction->getTitre()} ont été enregistrées");
            }
            
            $this->em->flush();

            return $this->redirectToRoute('app_configuration', $page_param === NULL ? [] : ['page'=>$page_param]);
        }
        
        $fonctions = $paginator->paginate(
            $this->repoFonction->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('configuration/index.html.twig', [
            'fonctions' => $fonctions,
            'form_fonction' => $form_fonction->createView(),
            'is_creating_fonction' => $is_creating_fonction,
            'page_param' =>$page_param
        ]);
    }

    #[Route('/configuration/fonction/{id}.delete', name: 'app_configuration_fonction_delete')]
    public function delete(Fonction $fonction){

        if($fonction->getId() !== NULL) {
            $this->em->remove($fonction);
            $this->em->flush();
        }

        return $this->redirectToRoute('app_configuration');

    }
}
