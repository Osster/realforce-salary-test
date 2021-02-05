<?php

namespace App\Repository;

use App\Entity\SalaryItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SalaryItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalaryItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalaryItem[]    findAll()
 * @method SalaryItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalaryItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SalaryItem::class);
    }

    // /**
    //  * @return SalaryItem[] Returns an array of SalaryItem objects
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
    public function findOneBySomeField($value): ?SalaryItem
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
