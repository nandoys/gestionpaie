<?php

namespace App\Repository;

use App\Entity\Agent;
use App\Entity\PretAgent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PretAgent>
 *
 * @method PretAgent|null find($id, $lockMode = null, $lockVersion = null)
 * @method PretAgent|null findOneBy(array $criteria, array $orderBy = null)
 * @method PretAgent[]    findAll()
 * @method PretAgent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PretAgentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PretAgent::class);
    }

    public function save(PretAgent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PretAgent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findFirstUnpaidPret(Agent $agent, string $typePret) {
        return $this->createQueryBuilder('p')
            ->andWhere('p.dateAt = (SELECT MIN(p2.dateAt) FROM App\Entity\PretAgent p2 where p2.estCloture = false and p2.agent = :agent)')
            ->andWhere('p.typePret = :typePret')
            ->andWhere('p.estCloture = false')
            ->andWhere('p.agent = :agent')
            ->setParameters(compact('agent', 'typePret'))
            ->getQuery()
            ->getOneOrNullResult();

    }

//    /**
//     * @return PretAgent[] Returns an array of PretAgent objects
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

//    public function findOneBySomeField($value): ?PretAgent
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
