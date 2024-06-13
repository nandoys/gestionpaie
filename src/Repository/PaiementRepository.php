<?php

namespace App\Repository;

use App\Entity\Agent;
use App\Entity\Paiement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Paiement>
 *
 * @method Paiement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paiement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paiement[]    findAll()
 * @method Paiement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaiementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paiement::class);
    }

    public function save(Paiement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Paiement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByAgentInPeriod($startPeriod, $endPeriod, $agent) {
        return $this->createQueryBuilder('p')
            ->where('p.dateAt >= :startPeriod')
            ->andWhere('p.dateAt <= :endPeriod')
            ->andWhere('p.agent = :agent')
            ->orderBy('p.dateAt', 'DESC')
            ->setParameters(compact('agent', 'startPeriod', 'endPeriod'))
            ->getQuery()
            ->getResult();
    }

    public function findPaymentsByDate($month, $agent, $paieId = 0) {
        return $this->createQueryBuilder('p')
                ->where('MONTH(p.dateAt) = :month')
                ->andWhere('p.id != :id')
                ->andWhere('p.agent = :agent')
                ->setParameters(['agent' => $agent, 'month' => $month, 'id' => $paieId])
                ->getQuery()
                ->getResult();
    }

    public function findAnnualNetPaymentGroupByAgent($debutDate, $finDate) {
        return $this->createQueryBuilder('p')
            ->select('SUM(p.cnss) AS cnss, SUM(p.ipr) AS ipr, SUM(p.avanceSalaire) AS avanceSalaire, SUM(p.pretLogement) AS pretLogement,
            SUM(p.pretFraisScolaire) AS pretFraisScolaire,SUM(p.pretDeuil) AS pretDeuil, SUM(p.pretAutre) AS pretAutre, SUM(p.base) AS base,
            SUM(p.primeDiplome) AS primeDiplome, SUM(p.heureSupplementaire) AS heureSupplementaire, SUM(p.transport) AS transport,
            SUM(p.logement) AS logement, SUM(p.allocationFamiliale) AS allocationFamiliale, SUM(p.autres) AS autres, SUM(p.abscence) AS abscence,
            SUM(p.exceptionnel) AS exceptionnel, a.id AS agent_id')
            ->where('p.dateAt >= :debutDate')
            ->andWhere('p.dateAt <= :finDate')
            ->setParameters(compact('debutDate', 'finDate'))
            ->groupBy('p.agent')
            ->orderBy('a.nom', 'ASC')
            ->join('p.agent', 'a')
            ->getQuery()
            ->getResult();
    }

    public function findQuarterNetPaymentGroupByAgent($debutMois, $debutAnnee, $finMois, $finAnnee) {
        return $this->createQueryBuilder('p')
            ->select('SUM(p.cnss) AS cnss, SUM(p.ipr) AS ipr, SUM(p.avanceSalaire) AS avanceSalaire, SUM(p.pretLogement) AS pretLogement,
            SUM(p.pretFraisScolaire) AS pretFraisScolaire,SUM(p.pretDeuil) AS pretDeuil, SUM(p.pretAutre) AS pretAutre, SUM(p.base) AS base,
            SUM(p.primeDiplome) AS primeDiplome, SUM(p.heureSupplementaire) AS heureSupplementaire, SUM(p.transport) AS transport,
            SUM(p.logement) AS logement, SUM(p.allocationFamiliale) AS allocationFamiliale, SUM(p.autres) AS autres, SUM(p.abscence) AS abscence,
            SUM(p.exceptionnel) AS exceptionnel, a.id AS agent_id')
            ->where('MONTH(p.dateAt) >= :debutMois AND YEAR(p.dateAt) = :debutAnnee')
            ->andWhere('MONTH(p.dateAt) <= :finMois AND YEAR(p.dateAt) = :finAnnee')
            ->setParameters(compact('debutMois', 'debutAnnee', 'finMois', 'finAnnee'))
            ->groupBy('p.agent')
            ->orderBy('a.nom', 'ASC')
            ->join('p.agent', 'a')
            ->getQuery()
            ->getResult();
    }

    public function findMonthNetPaymentGroupByAgent($mois, $annee) {
        return $this->createQueryBuilder('p')
            ->select('SUM(p.cnss) AS cnss, SUM(p.ipr) AS ipr, SUM(p.avanceSalaire) AS avanceSalaire, SUM(p.pretLogement) AS pretLogement,
            SUM(p.pretFraisScolaire) AS pretFraisScolaire,SUM(p.pretDeuil) AS pretDeuil, SUM(p.pretAutre) AS pretAutre, SUM(p.base) AS base,
            SUM(p.primeDiplome) AS primeDiplome, SUM(p.heureSupplementaire) AS heureSupplementaire, SUM(p.transport) AS transport,
            SUM(p.logement) AS logement, SUM(p.allocationFamiliale) AS allocationFamiliale, SUM(p.autres) AS autres, SUM(p.abscence) AS abscence,
            SUM(p.exceptionnel) AS exceptionnel, a.id AS agent_id')
            ->where('MONTH(p.dateAt) = :mois')
            ->andWhere('YEAR(p.dateAt) = :annee')
            ->setParameters(compact('mois', 'annee'))
            ->groupBy('p.agent')
            ->orderBy('a.nom', 'ASC')
            ->join('p.agent', 'a')
            ->getQuery()
            ->getResult();
    }
    
    public function findPaymentBill($month, $annee, Agent $agent) {
        return $this->createQueryBuilder('p')
            ->select('SUM(p.cnss) AS cnss, SUM(p.ipr) AS ipr, SUM(p.avanceSalaire) AS avanceSalaire, SUM(p.pretLogement) AS pretLogement,
            SUM(p.pretFraisScolaire) AS pretFraisScolaire,SUM(p.pretDeuil) AS pretDeuil, SUM(p.pretAutre) AS pretAutre, SUM(p.base) AS base,
            SUM(p.primeDiplome) AS primeDiplome, SUM(p.heureSupplementaire) AS heureSupplementaire, SUM(p.transport) AS transport,
            SUM(p.logement) AS logement, SUM(p.allocationFamiliale) AS allocationFamiliale, SUM(p.autres) AS autres, SUM(p.abscence) AS abscence,
            SUM(p.exceptionnel) AS exceptionnel, a.id AS agent_id')
            ->where('MONTH(p.dateAt) = :month')
            ->andWhere('YEAR(p.dateAt) = :annee')
            ->andWhere('p.agent = :agent')
            ->setParameters(compact('month', 'annee', 'agent'))
            ->groupBy('p.agent')
            ->orderBy('a.nom', 'ASC')
            ->join('p.agent', 'a')
            ->getQuery()
            ->getResult();
    }
    
    public function findAllPaymentBill($month, $year) {
        return $this->createQueryBuilder('p')
            ->select('SUM(p.cnss) AS cnss, SUM(p.ipr) AS ipr, SUM(p.avanceSalaire) AS avanceSalaire, SUM(p.pretLogement) AS pretLogement,
            SUM(p.pretFraisScolaire) AS pretFraisScolaire,SUM(p.pretDeuil) AS pretDeuil, SUM(p.pretAutre) AS pretAutre, SUM(p.base) AS base,
            SUM(p.primeDiplome) AS primeDiplome, SUM(p.heureSupplementaire) AS heureSupplementaire, SUM(p.transport) AS transport,
            SUM(p.logement) AS logement, SUM(p.allocationFamiliale) AS allocationFamiliale, SUM(p.autres) AS autres, SUM(p.abscence) AS abscence,
            SUM(p.exceptionnel) AS exceptionnel, a.id AS agent_id')
            ->where('MONTH(p.dateAt) = :month')
            ->andWhere('YEAR(p.dateAt) = :year')
            ->setParameters(compact('month', 'year'))
            ->groupBy('p.agent')
            ->orderBy('a.nom', 'ASC')
            ->join('p.agent', 'a')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Paiement[] Returns an array of Paiement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Paiement
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
