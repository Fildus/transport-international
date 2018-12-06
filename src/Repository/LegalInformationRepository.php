<?php

namespace App\Repository;

use App\Entity\LegalInformation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LegalInformation|null find($id, $lockMode = null, $lockVersion = null)
 * @method LegalInformation|null findOneBy(array $criteria, array $orderBy = null)
 * @method LegalInformation[]    findAll()
 * @method LegalInformation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LegalInformationRepository extends ServiceEntityRepository
{
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(RegistryInterface $registry, CacheInterface $cache)
    {
        parent::__construct($registry, LegalInformation::class);
        $this->cache = $cache;
    }

    /**
     * @param string|null $el
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findByCorporateName(?string $el): ?array
    {
        $key = 'findByCorporateName122654';
        if (!$this->cache->has($key)) {
            $this->cache->set($key, $this->findAll());
        }
        $returnArray = [];

        /**
         * @var $r LegalInformation
         */
        foreach ($this->cache->get($key) as $r) {
            if (preg_match('/' . strtolower($el) . '/', strtolower($r->getCorporateName()))) {
                if (!in_array($r->getCorporateName(), $returnArray)) {
                    $returnArray[] = (string)$r->getCorporateName();
                }
            }
        }

        return $returnArray;
    }

    /**
     * @param $el
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findByLegalForm($el): ?array
    {
        $key = 'findByLegalForm-556699';
        if (!$this->cache->has($key)) {
            $this->cache->set($key, $this->findAll());
        }
        $returnArray = [];

        /**
         * @var $r LegalInformation
         */
        foreach ($this->cache->get($key) as $r) {
            if (preg_match('/' . strtolower($el) . '/', strtolower($r->getLegalForm()))) {
                if (!in_array($r->getLegalForm(), $returnArray)) {
                    $returnArray[] = (string)$r->getLegalForm();
                }
            }
        }

        return $returnArray;
    }

    /**
     * @param $el
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findByCompanyName($el): ?array
    {
        $key = 'findByCompanyName-5d988a';
        if (!$this->cache->has($key)) {
            $this->cache->set($key, $this->findAll());
        }
        $returnArray = [];

        /**
         * @var $r LegalInformation
         */
        foreach ($this->cache->get($key) as $r) {
            if (preg_match('/' . strtolower($el) . '/', strtolower($r->getCompanyName()))) {
                if (!in_array($r->getCompanyName(), $returnArray)) {
                    $returnArray[] = (string)$r->getCompanyName();
                }
            }
        }

        return $returnArray;
    }

    /**
     * @param $el
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findBySiret($el): ?array
    {
        $key = 'findBySiret-54da4d';
        if (!$this->cache->has($key)) {
            $this->cache->set($key, $this->findAll());
        }
        $returnArray = [];

        /**
         * @var $r LegalInformation
         */
        foreach ($this->cache->get($key) as $r) {
            if (preg_match('/' . strtolower($el) . '/', strtolower($r->getSiret()))) {
                if (!in_array($r->getSiret(), $returnArray)) {
                    $returnArray[] = (string)$r->getSiret();
                }
            }
        }

        return $returnArray;
    }
}
