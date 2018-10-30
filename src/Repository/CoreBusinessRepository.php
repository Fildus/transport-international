<?php

namespace App\Repository;

use App\Entity\CoreBusiness;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CoreBusiness|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoreBusiness|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoreBusiness[]    findAll()
 * @method CoreBusiness[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoreBusinessRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CoreBusiness::class);
    }

//    /**
//     * @return CoreBusiness[] Returns an array of CoreBusiness objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CoreBusiness
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
