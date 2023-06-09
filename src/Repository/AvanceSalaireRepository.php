<?php

namespace App\Repository;

use App\Entity\Agent;
use App\Entity\AvanceSalaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AvanceSalaire>
 *
 * @method AvanceSalaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvanceSalaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvanceSalaire[]    findAll()
 * @method AvanceSalaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvanceSalaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvanceSalaire::class);
    }

    public function save(AvanceSalaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AvanceSalaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findFirstUnpaidAvanceSalaire(Agent $agent) {
        return $this->createQueryBuilder('a')
            ->andWhere('a.dateAt = (SELECT MIN(a2.dateAt) FROM App\Entity\AvanceSalaire a2 where a2.estCloture = false and a2.agent = :agent)')
            ->andWhere('a.estCloture = false')
            ->andWhere('a.agent = :agent')
            ->setParameters(['agent'=>$agent])
            ->getQuery()
            ->getOneOrNullResult();

    }

//    /**
//     * @return AvanceSalaire[] Returns an array of AvanceSalaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AvanceSalaire
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
