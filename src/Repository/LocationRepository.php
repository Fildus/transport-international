<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(RegistryInterface $registry, CacheInterface $cache)
    {
        parent::__construct($registry, Location::class);
        $this->cache = $cache;
    }

    /**
     * @param $el
     * @return array|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findByAddress($el): ?array
    {
        $key = 'findByAddress-z665d2';
        if (!$this->cache->has($key)) {
            $this->cache->set($key, $this->findAll());
        }
        $returnArray = [];

        /**
         * @var $r Location
         */
        foreach ($this->cache->get($key) as $r) {
            if (preg_match('/' . strtolower($el) . '/', strtolower($r->getAddress()))) {
                if (!in_array($r->getAddress(), $returnArray)) {
                    $returnArray[] = (string)$r->getAddress();
                }
            }
        }

        return $returnArray;
    }

    /**
     * @param $el
     * @return array|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findByPostalCode($el): ?array
    {
        $key = 'findByPostalCode-a5z4d1';
        if (!$this->cache->has($key)) {
            $this->cache->set($key, $this->findAll());
        }
        $returnArray = [];

        /**
         * @var $r Location
         */
        foreach ($this->cache->get($key) as $r) {
            if (preg_match('/' . strtolower($el) . '/', strtolower($r->getPostalCode()))) {
                if (!in_array($r->getPostalCode(), $returnArray)) {
                    $returnArray[] = (string)$r->getPostalCode();
                }
            }
        }

        return $returnArray;
    }

    /**
     * @param $el
     * @return array|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findByCity($el): ?array
    {
        $key = 'findByCity-a665z1';
        if (!$this->cache->has($key)) {
            $this->cache->set($key, $this->findAll());
        }
        $returnArray = [];

        /**
         * @var $r Location
         */
        foreach ($this->cache->get($key) as $r) {
            if (preg_match('/' . strtolower($el) . '/', strtolower($r->getCity()))) {
                if (!in_array($r->getCity(), $returnArray)) {
                    $returnArray[] = (string)$r->getCity();
                }
            }
        }

        return $returnArray;
    }

}
