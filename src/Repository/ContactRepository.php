<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(RegistryInterface $registry, CacheInterface $cache)
    {
        parent::__construct($registry, Contact::class);
        $this->cache = $cache;
    }

    /**
     * @param string|null $el
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findByPhone(string $el):?array
    {
        $key = 'findByPhone-az54za';
        if (!$this->cache->has($key)) {
            $this->cache->set($key, $this->findAll());
        }
        $returnArray = [];

        /**
         * @var $r Contact
         */
        foreach ($this->cache->get($key) as $r) {
            if (preg_match('/' . strtolower($el) . '/', strtolower($r->getPhone()))) {
                if (!in_array($r->getPhone(), $returnArray)) {
                    $returnArray[] = (string)$r->getPhone();
                }
            }
        }

        return $returnArray;
    }

    /**
     * @param string $el
     * @return array|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findByFax(string $el):?array
    {
        $key = 'findByFax-az54za';
        if (!$this->cache->has($key)) {
            $this->cache->set($key, $this->findAll());
        }
        $returnArray = [];

        /**
         * @var $r Contact
         */
        foreach ($this->cache->get($key) as $r) {
            if (preg_match('/' . strtolower($el) . '/', strtolower($r->getFax()))) {
                if (!in_array($r->getFax(), $returnArray)) {
                    $returnArray[] = (string)$r->getFax();
                }
            }
        }

        return $returnArray;
    }

    /**
     * @param string $el
     * @return array|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findByContact(string $el):?array
    {
        $key = 'findByContact-az54za';
        if (!$this->cache->has($key)) {
            $this->cache->set($key, $this->findAll());
        }
        $returnArray = [];

        /**
         * @var $r Contact
         */
        foreach ($this->cache->get($key) as $r) {
            if (preg_match('/' . strtolower($el) . '/', strtolower($r->getContact()))) {
                if (!in_array($r->getContact(), $returnArray)) {
                    $returnArray[] = (string)$r->getContact();
                }
            }
        }

        return $returnArray;
    }

    /**
     * @param string $el
     * @return array|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findByWebSite(string $el):?array
    {
        $key = 'findByWebSite-az54za';
        if (!$this->cache->has($key)) {
            $this->cache->set($key, $this->findAll());
        }
        $returnArray = [];

        /**
         * @var $r Contact
         */
        foreach ($this->cache->get($key) as $r) {
            if (preg_match('/' . strtolower($el) . '/', strtolower($r->getWebSite()))) {
                if (!in_array($r->getWebSite(), $returnArray)) {
                    $returnArray[] = (string)$r->getWebSite();
                }
            }
        }

        return $returnArray;
    }

}
