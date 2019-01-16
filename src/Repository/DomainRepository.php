<?php

namespace App\Repository;

use App\Entity\Domain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method Domain|null find($id, $lockMode = null, $lockVersion = null)
 * @method Domain|null findOneBy(array $criteria, array $orderBy = null)
 * @method Domain[]    findAll()
 * @method Domain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DomainRepository extends ServiceEntityRepository
{
    /**
     * @var CacheInterface
     */
    private $cache;
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RegistryInterface $registry, CacheInterface $cache, RequestStack $requestStack)
    {
        parent::__construct($registry, Domain::class);
        $this->cache = $cache;
        $this->requestStack = $requestStack;
    }

    /**
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getAll(): array
    {
        $key = 'getAll-DomainRepository-26564';
        if (!$this->cache->has($key)) {
            $this->cache->set(
                $key,
                $this->createQueryBuilder('d')
                    ->leftJoin('d.activity', 'a')
                    ->addSelect('a')
                    ->getQuery()
                    ->getResult(),
                3600);
        }
        return $this->cache->get($key);
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function allQuery(): \Doctrine\ORM\Query
    {
        return $this->createQueryBuilder('d')->getQuery();

    }

}
