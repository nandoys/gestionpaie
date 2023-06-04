<?php

namespace App\Repository;

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

    public function findPaymentsByDate($month, $agent, $paieId = 0) {
        return $this->createQueryBuilder('p')
                ->where('MONTH(p.dateAt) = :month')
                ->andWhere('p.id != :id')
                ->andWhere('p.agent = :agent')
                ->setParameters(['agent' => $agent, 'month' => $month, 'id' => $paieId])
                ->getQuery()
                ->getResult();
    }

    /**
     * -id: 17
    -cnss: 0.0
    -ipr: 0.0
    -avanceSalaire: 60000.0
    -pretLogement: 0.0
    -pretFraisScolaire: 0.0
    -pretDeuil: 0.0
    -pretAutre: 0.0
    -dateAt: DateTime @1685750400 {#2038 ▶}
    -base: 200000.0
    -primeDiplome: 10000.0
    -heureSupplementaire: 0.0
    -transport: 50000.0
    -logement: 0.0
    -allocationFamiliale: 0.0
    -autres: 0.0
    -agent: App\Entity\Agent {#922 ▶}
    -abscence: 0.0
    -deductionPrecedente: 0
     */

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
