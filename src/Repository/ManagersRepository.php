<?php

namespace App\Repository;

use App\Entity\Managers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Managers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Managers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Managers[]    findAll()
 * @method Managers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManagersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Managers::class);
    }

}
