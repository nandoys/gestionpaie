<?php

namespace App\Controller;

use App\Form\FiltreMoisType;
use App\Repository\AgentRepository;
use App\Repository\ExerciceRepository;
use App\Repository\PaiementRepository;
use App\Service\DateRange;
use App\Service\DenormaliseurPaie;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RapportController extends AbstractController
{
    public function __construct(private PaginatorInterface $paginator, private UrlGeneratorInterface $router, private EntityManagerInterface $em){

    }
    #[Route('/rapports', name: 'rapport_index')]
    public function index(ExerciceRepository $repoExercice, PaiementRepository $repoPaie, AgentRepository $repoAgent, Request $request): Response
    {
        if (!$request->query->has('annee-cloture'))
        {
            return $this->redirectToRoute('rapport_index', ['annee-cloture'=>false]);
        }

        $exercice = $repoExercice->findOneByEstCloture(false);

        $dateRange = new DateRange();

        $dateRange->getMonthsInRange($exercice->getDebutAnnee(), $exercice->getFinAnnee());

        $trimestres = $dateRange->splitIntoChunks(3);

        $paiementsTrimestriel = [];

        for($i = 0; $i < count($trimestres); $i++) {
            $l = count($trimestres[$i]) - 1;

            $paiementsQueryResults = $repoPaie->findQuarterNetPaymentGroupByAgent(
                $trimestres[$i][0]['mois'],$trimestres[$i][0]['annee'],$trimestres[$i][$l]['mois'],$trimestres[$i][$l]['annee'],
            );

            $paiements = new DenormaliseurPaie($paiementsQueryResults, $repoAgent, $this->em);

            $paiementsTrimestriel[] = $paiements->getDenormalizedData();
        }

        $paiementsQueryResults = $repoPaie->findAnnualNetPaymentGroupByAgent($exercice->getDebutAnnee(), $exercice->getFinAnnee());

        $paiements = new DenormaliseurPaie($paiementsQueryResults, $repoAgent, $this->em);

        $paiements = $this->paginator->paginate(
            $paiements->getDenormalizedData(),
            $request->query->getInt('page', 1),
            10);

        $formFiltreMois = $this->createForm(FiltreMoisType::class, null, [
            'minMoisFiltre' =>  $exercice->getDebutAnnee()->format('m-Y'),
            'maxMoisFiltre' =>  $exercice->getFinAnnee()->format('m-Y'),
        ]);

        $formFiltreMois->handleRequest($request);

        if ($formFiltreMois->isSubmitted() && $formFiltreMois->isValid()) {
            $paiementsQueryResults = $repoPaie->findMonthNetPaymentGroupByAgent($formFiltreMois->getData()['filtreMois']->format('m'));
            $paiements = new DenormaliseurPaie($paiementsQueryResults, $repoAgent, $this->em);

            $paiements = $this->paginator->paginate(
                $paiements->getDenormalizedData(),
                $request->query->getInt('page', 1),
                10);
        }

        return $this->render('rapport/index.twig', [
            'paiements' => $paiements,
            'paiementsTrimestriel' => $paiementsTrimestriel,
            'minMoisFiltre' => $exercice->getDebutAnnee()->format('Y-m'),
            'maxMoisFiltre' => $exercice->getFinAnnee()->format('Y-m'),
            'formFiltreMois' => $formFiltreMois->createView(),
            'isTrimestre' => $request->query->has('trimestre')
        ]);
    }
}