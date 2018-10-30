<?php

namespace App\Repository;

use App\Entity\LegalInformation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LegalInformation|null find($id, $lockMode = null, $lockVersion = null)
 * @method LegalInformation|null findOneBy(array $criteria, array $orderBy = null)
 * @method LegalInformation[]    findAll()
 * @method LegalInformation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LegalInformationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LegalInformation::class);
    }

//    /**
//     * @return LegalInformation[] Returns an array of LegalInformation objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LegalInformation
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
