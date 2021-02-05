<?php

namespace App\Repository;

use App\Entity\SalaryRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SalaryRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalaryRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalaryRate[]    findAll()
 * @method SalaryRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalaryRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SalaryRate::class);
    }

    public function getActual(int $person_id, \DateTime $date)
    {
        $to = new \DateTime($date->format("Y-m-d") . " 23:59:59");

        $qb = $this
            ->createQueryBuilder("e")
            ->orderBy("e.start_at", "desc")
            ->andWhere('e.person_id = :person_id')
            ->andWhere('e.start_at <= :to')
            ->setParameter('person_id', $person_id)
            ->setParameter('to', $to)
            ->getQuery();

        $result = $qb->getOneOrNullResult();

        return $result;
    }

    // /**
    //  * @return SalaryRate[] Returns an array of SalaryRate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SalaryRate
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
