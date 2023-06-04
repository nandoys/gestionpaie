<?php

namespace App\Controller;

use App\Entity\Diplome;
use App\Entity\Fonction;
use App\Form\DiplomeType;
use App\Form\FonctionType;
use App\Repository\DiplomeRepository;
use App\Repository\FonctionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConfigurationController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em, private FonctionRepository $repoFonction, private DiplomeRepository $repoDiplome)
    {}

    #[Route('/configuration/fonction', name: 'app_configuration_fonction')]
    #[Route('/configuration/fonction/{id}/update', name: 'app_configuration_fonction_update')]
    public function index_fonction(PaginatorInterface $paginator, Request $request, Fonction $fonction): Response
    {
        $page_param = $request->get('page');

        // check the mode to handle modal form
        $is_creating_fonction = $fonction->getId() === NULL;

        $form_fonction = $this->createForm(FonctionType::class, $fonction);

        if ($form_fonction->isSubmitted() && $form_fonction->isvalid()) {

            if ($is_creating_fonction) {
                $this->em->persist($fonction);

                $this->addFlash('success', "Vous venez d'ajouter une nouvelle fonction {$fonction->getTitre()}");
            } else {
                $this->addFlash('success', "Vos modifications sur sur la fonction {$fonction->getTitre()} ont été enregistrées");
            }

            $this->em->flush();

            return $this->redirectToRoute('app_configuration_fonction', $page_param === NULL ? [] : ['page' => $page_param]);
        }

        $fonctions = $paginator->paginate(
            $this->repoFonction->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('configuration/fonctions/index.html.twig', [
            'fonctions' => $fonctions,
            'form_fonction' => $form_fonction->createView(),
            'is_creating_fonction' => $is_creating_fonction,
            'page_param' => $page_param
        ]);
    }

    #[Route('/configuration/fonction/{id}.delete', name: 'app_configuration_fonction_delete')]
    public function delete_fonction(Fonction $fonction): RedirectResponse
    {

        if ($fonction->getId() !== NULL) {
            $this->em->remove($fonction);
            $this->em->flush();
        }

        return $this->redirectToRoute('app_configuration_fonction');
    }


    #[Route('/configuration/diplome', name: 'app_configuration_diplome')]
    #[Route('/configuration/diplome/{id}/update', name: 'app_configuration_diplome_update')]
    public function index_diplome(PaginatorInterface $paginator, Request $request, Diplome $diplome): Response
    {
        $page_param = $request->get('page');

        // check the mode to handle modal form
        $is_creating_diplome = $diplome->getId() === NULL;

        $form_diplome = $this->createForm(DiplomeType::class, $diplome);

        $form_diplome->handleRequest($request);

        if ($form_diplome->isSubmitted() && $form_diplome->isvalid()) {

            if ($is_creating_diplome) {
                $this->em->persist($diplome);

                $this->addFlash('success', "Vous venez d'ajouter une nouvelle fonction {$diplome->getTitre()}");
            } else {
                $this->addFlash('success', "Vos modifications sur sur la fonction {$diplome->getTitre()} ont été enregistrées");
            }

            $this->em->flush();

            return $this->redirectToRoute('app_configuration_diplome', $page_param === NULL ? [] : ['page' => $page_param]);
        }

        $diplomes = $paginator->paginate(
            $this->repoDiplome->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('configuration/fonctions/index.html.twig', [
            'diplomes' => $diplomes,
            'form_diplome' => $form_diplome->createView(),
            'is_creating_diplome' => $is_creating_diplome,
            'page_param' => $page_param
        ]);
    }

    #[Route('/configuration/diplome/{id}.delete', name: 'app_configuration_diplome_delete')]
    public function delete_diplome(Fonction $fonction): RedirectResponse
    {

        if ($fonction->getId() !== NULL) {
            $this->em->remove($fonction);
            $this->em->flush();
        }

        return $this->redirectToRoute('app_configuration');
    }
}
