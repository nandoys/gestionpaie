<?php

namespace App\Controller;

use App\Entity\Diplome;
use App\Entity\Exercice;
use App\Entity\Fonction;
use App\Form\DiplomeType;
use App\Form\ExerciceType;
use App\Form\FonctionType;
use App\Repository\DiplomeRepository;
use App\Repository\ExerciceRepository;
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
    public function __construct(private EntityManagerInterface $em, private FonctionRepository $repoFonction,
                                private DiplomeRepository $repoDiplome, private ExerciceRepository $repoExercice)
    {}

    #[Route('/configuration/fonction', name: 'app_configuration_fonction')]
    #[Route('/configuration/fonction/{id}/update', name: 'app_configuration_fonction_update')]
    public function index_fonction(PaginatorInterface $paginator, Request $request, Fonction $fonction): Response
    {
        $page_param = $request->get('page');

        // check the mode to handle modal form
        $is_creating_fonction = $fonction->getId() === NULL;

        $form_fonction = $this->createForm(FonctionType::class, $fonction);

        $form_fonction->handleRequest($request);

        if ($form_fonction->isSubmitted() && $form_fonction->isvalid()) {

            if ($is_creating_fonction) {
                $this->em->persist($fonction);

                $this->addFlash('success', "Vous venez d'ajouter une nouvelle fonction {$fonction->getTitre()}");
            } else {
                $this->addFlash('success', "Vos modifications sur la fonction {$fonction->getTitre()} ont été enregistrées");
            }

            $this->em->flush();

            return $this->redirectToRoute('app_configuration_fonction', $page_param === NULL ? [] : ['page' => $page_param]);
        }

        $fonctions = $paginator->paginate(
            $this->repoFonction->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('configuration/fonction.twig', [
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

        $form_diplome = $this->createForm(DiplomeType::class, $diplome);

        $form_diplome->handleRequest($request);

        if ($form_diplome->isSubmitted() && $form_diplome->isvalid()) {

            $this->addFlash('success', "Vos modifications sur sur la fonction {$diplome->getTitre()} ont été enregistrées");

            $this->em->flush();

            return $this->redirectToRoute('app_configuration_diplome', $page_param === NULL ? [] : ['page' => $page_param]);
        }

        $diplomes = $paginator->paginate(
            $this->repoDiplome->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('configuration/diplome.twig', [
            'diplomes' => $diplomes,
            'form_diplome' => $form_diplome->createView(),
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


    #[Route('/configuration/exercice', name: 'app_configuration_exercice')]
    #[Route('/configuration/exercice/{id}/update', name: 'app_configuration_exercice_update')]
    public function index_exercice(PaginatorInterface $paginator, Request $request, Exercice $exercice): Response
    {
        $page_param = $request->get('page');

        // check the mode to handle modal form
        $is_creating_exercice = $exercice->getId() === NULL;

        $form_exercice = $this->createForm(ExerciceType::class, $exercice);

        $form_exercice->handleRequest($request);


        if (count($this->repoExercice->findAll()) == 1) {
            $exerciceInactive = $this->repoExercice->findOneBy(['estCloture' => true]);

            if ($exerciceInactive != NULL) {
                $exerciceInactive->setEstCloture(false);
                $this->em->flush();
            }
        }

        if ($form_exercice->isSubmitted() && $form_exercice->isvalid()) {

            if ($is_creating_exercice) {

                $exerciceEnCours = $this->repoExercice->findOneBy(['estCloture' => false]);

                if ($exerciceEnCours != NULL) {
                    $exerciceEnCours->setEstCloture(true);
                }

                $this->em->persist($exercice);

                $this->addFlash('success', "Vous venez d'ajouter un nouveau exercice");
            } else {
                $this->addFlash('success', "Vos modifications ont été enregistrées");
            }

            $this->em->flush();

            return $this->redirectToRoute('app_configuration_exercice', $page_param === NULL ? [] : ['page' => $page_param]);
        }

        $exercices = $paginator->paginate(
            $this->repoExercice->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('configuration/exercice.twig', [
            'exercices' => $exercices,
            'form_exercice' => $form_exercice->createView(),
            'is_creating_exercice' => $is_creating_exercice,
            'page_param' => $page_param
        ]);
    }

    #[Route('/configuration/diplome/{id}.delete', name: 'app_configuration_diplome_delete')]
    public function delete_exercice(Fonction $fonction): RedirectResponse
    {

        if ($fonction->getId() !== NULL) {
            $this->em->remove($fonction);
            $this->em->flush();
        }

        return $this->redirectToRoute('app_configuration');
    }

    #[Route('/configuration/exercice/{id}/cloturer', name: 'app_configuration_exercice_close')]
    public function close_exercice(Exercice $exercice, ExerciceRepository $repoExercice): RedirectResponse {

        $count = $repoExercice->count(['estCloture' => false]);

        if ($count == 1) {
            $this->addFlash('success', "L'exercice {$exercice->getDebutAnnee()->format('d/m/Y')} - {$exercice->getFinAnnee()->format('d/m/Y')} ne peut pas être clôturé. Au moins une année doit être active");

            return $this->redirectToRoute('app_configuration_exercice');
        }

        $exercice->setEstCloture(true);
        $this->em->flush();

        $this->addFlash('success', "L'exercice {$exercice->getDebutAnnee()->format('d/m/Y')} - {$exercice->getFinAnnee()->format('d/m/Y')} a été clôturé");

        return $this->redirectToRoute('app_configuration_exercice');
    }

    #[Route('/configuration/exercice/{id}/activer', name: 'app_configuration_exercice_activate')]
    public function activate_exercice(Exercice $exercice, ExerciceRepository $repoExercice): RedirectResponse {

        foreach ($repoExercice->findAll() as $exercices) {
            $exercices->setEstCloture(true);
        }
        $exercice->setEstCloture(false);
        $this->em->flush();

        $this->addFlash('success', "L'exercice {$exercice->getDebutAnnee()->format('d/m/Y')} - {$exercice->getFinAnnee()->format('d/m/Y')} a été activé");

        return $this->redirectToRoute('app_configuration_exercice');
    }
}
