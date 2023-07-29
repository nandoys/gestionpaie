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
    public function findUnpaidAvanceSalaire(Agent $agent, \DateTimeInterface $dateAt) {
 
        return $this->createQueryBuilder('a')
            ->andWhere('a.dateAt < :dateAt')
            ->andWhere('a.estCloture = false')
            ->andWhere('a.agent = :agent')
            ->setParameters(['agent'=>$agent, 'dateAt'=>$dateAt->format('Y-m-d')])
            ->getQuery()
            ->getResult();

    }
}
