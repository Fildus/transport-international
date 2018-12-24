<?php

namespace App\Repository;

use App\Entity\About;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method About|null find($id, $lockMode = null, $lockVersion = null)
 * @method About|null findOneBy(array $criteria, array $orderBy = null)
 * @method About[]    findAll()
 * @method About[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AboutRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, About::class);
    }

}
