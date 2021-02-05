<?php

namespace App\Repository;

use App\Entity\SalaryTotal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SalaryTotal|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalaryTotal|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalaryTotal[]    findAll()
 * @method SalaryTotal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalaryTotalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SalaryTotal::class);
    }

    // /**
    //  * @return SalaryTotal[] Returns an array of SalaryTotal objects
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
    public function findOneBySomeField($value): ?SalaryTotal
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
