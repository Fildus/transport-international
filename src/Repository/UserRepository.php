<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Search\UserSearch;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(RegistryInterface $registry, CacheInterface $cache)
    {
        parent::__construct($registry, User::class);
        $this->cache = $cache;
    }

    /**
     * @param string $mail
     *
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findByMailLike(string $mail)
    {
        $key = 'findByMailLike-d6dz55z';
        if (!$this->cache->has($key)) {
            $this->cache->set($key, $this->findAll());
        }
        $returnArray = [];

        /**
         * @var $m User
         */
        foreach ($this->cache->get($key) as $m) {
            if (preg_match('/' . strtolower($mail) . '/', strtolower($m->getUsername()))) {
                if (!in_array($m->getUsername(), $returnArray)) {
                    $returnArray[] = (string)$m->getUsername();
                }
            }
        }
        return $returnArray;
    }

    /**
     * @param UserSearch $search
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllUsers(UserSearch $search)
    {
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.id', 'ASC');

        if ($search->getRole() !== null) {
            $qb
                ->andWhere('u.role = :role')
                ->setParameter('role', $search->getRole());
        }

        if ($search->getMail() !== null) {
            $qb
                ->andWhere('u.username = :mail')
                ->setParameter('mail', $search->getMail());
        }

        return $qb->getQuery();
    }

}
