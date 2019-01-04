<?php

namespace App\Repository;

use App\Entity\Contract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Contract|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contract|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contract[]    findAll()
 * @method Contract[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Contract::class);
    }

    /**
     * @param int|null $client
     * @return Query
     */
    public function allQuery(?int $client): Query
    {
        $qb = $this->createQueryBuilder('contract');
        if ($client !== null && $client !== 0) {
            $qb = $qb->innerJoin('contract.client', 'client')
            ->where('client.id =' . $client);
        }
        return $qb->getQuery();
    }
}
