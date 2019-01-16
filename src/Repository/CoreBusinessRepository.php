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

}
