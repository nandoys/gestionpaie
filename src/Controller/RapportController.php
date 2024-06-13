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
        $is_filter_year = true;

        if (count($repoExercice->findAll()) == 0) {
            $this->addFlash('warning', "Vous devez avoir un exercice avant d'accéder au module rapport! Veuillez en créer un");
            return $this->redirectToRoute('app_configuration_exercice');
        }

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
                $trimestres[$i][0]['mois'], $trimestres[$i][0]['annee'],$trimestres[$i][$l]['mois'],$trimestres[$i][$l]['annee'],
            );

            $paiements = new DenormaliseurPaie($paiementsQueryResults, $repoAgent, $this->em);

            $count = 10;
            if(count($paiements->getDenormalizedData()) > 0) {
                $count = count($paiements->getDenormalizedData());
            }

            $paginator = $this->paginator->paginate(
                $paiements->getDenormalizedData(),
                $request->query->getInt('page-trimestre-'.$i +1, 1),
                $count);

            $paginator->setPaginatorOptions(['pageParameterName' => 'page-trimestre-'.$i +1]);

            $paiementsTrimestriel[] = $paginator;
        }

        $paiementsQueryResults = $repoPaie->findAnnualNetPaymentGroupByAgent($exercice->getDebutAnnee(), $exercice->getFinAnnee());

        $paiements = new DenormaliseurPaie($paiementsQueryResults, $repoAgent, $this->em);

        $count = 10;
        if(count($paiements->getDenormalizedData()) > 0) {
            $count = count($paiements->getDenormalizedData());
        }

        $paiements = $this->paginator->paginate(
            $paiements->getDenormalizedData(),
            $request->query->getInt('page', 1),
            $count);

        $formFiltreMois = $this->createForm(FiltreMoisType::class, null, [
            'minMoisFiltre' =>  $exercice->getDebutAnnee()->format('m-Y'),
            'maxMoisFiltre' =>  $exercice->getFinAnnee()->format('m-Y'),
        ]);

        $formFiltreMois->handleRequest($request);

        if ($formFiltreMois->isSubmitted() && $formFiltreMois->isValid()) {
            $mois = $formFiltreMois->getData()['filtreMois']->format('m');
            $annee = $formFiltreMois->getData()['filtreMois']->format('Y');
            $paiementsQueryResults = $repoPaie->findMonthNetPaymentGroupByAgent($mois, $annee);
            $paiements = new DenormaliseurPaie($paiementsQueryResults, $repoAgent, $this->em);
            
            $count = 10;
            if(count($paiements->getDenormalizedData()) > 0) {
                $count = count($paiements->getDenormalizedData());
            }

            $paiements = $this->paginator->paginate(
                $paiements->getDenormalizedData(),
                $request->query->getInt('page', 1),
                $count);

            $is_filter_year = false;

        }

        // sous total
        $totalBrutImposable = 0;
        $totalIndemnite = 0;
        $totalSalaireBrut = 0;
        $totalDeduction = 0;
        $totalNetAPayer = 0;

        foreach ($paiements->getItems() as $paie) {
            $totalBrutImposable += $paie->calculBrutImposable();
            $totalIndemnite += $paie->calculTotalIndemnite();
            $totalSalaireBrut += $paie->calculSalaireBrut();
            $totalDeduction += $paie->calculDeduction();
            $totalNetAPayer += $paie->calculNetAPayer();
        }
        
        $sous_totaux = compact('totalBrutImposable', 'totalIndemnite', 'totalSalaireBrut', 'totalDeduction', 'totalNetAPayer');

        return $this->render('rapport/index.twig', [
            'paiements' => $paiements,
            'sous_totaux' => $sous_totaux,
            'paiementsTrimestriel' => $paiementsTrimestriel,
            'minMoisFiltre' => $exercice->getDebutAnnee()->format('Y-m'),
            'maxMoisFiltre' => $exercice->getFinAnnee()->format('Y-m'),
            'formFiltreMois' => $formFiltreMois->createView(),
            'isTrimestre' => $request->query->has('trimestre'),
            'is_filter_year' => $is_filter_year,
            'formData' => $formFiltreMois->getData(),
            'debut_exercice' => $exercice->getDebutAnnee()->format('Y'),
            'fin_exercice' => $exercice->getFinAnnee()->format('Y')
        ]);
    }
}