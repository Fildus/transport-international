<?php

namespace App\Repository;

use App\Entity\LegalInformation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use mysql_xdevapi\Result;
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
     *
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
     *
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
     *
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findByCompanyName($el): array
    {
        $key = md5(__CLASS__ . __METHOD__ . $el);
        if (!$this->cache->has($key)) {
            $strings = explode(' ', $el);

            $qb = $this->createQueryBuilder('l');

            for ($i = 0, $iMax = count($strings); $i < $iMax; $i++) {
                $qb = $qb
                    ->andWhere('l.companyName LIKE :var' . $i)
                    ->setParameter('var' . $i, '%' . $strings[$i] . '%');
            }
            $qb = $qb
                ->getQuery()
                ->setMaxResults(30)
                ->getResult();

            $return = [];
            /** @var $item LegalInformation */
            foreach ($qb as $item) {
                $return[] = $item->getCompanyName();
            }
            $this->cache->set($key, array_values(array_unique($return)), 3600);
        }
        return $this->cache->get($key);
    }

    /**
     * @param $el
     *
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

    /**
     * @param int $max
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findAllOnlySlug(int $max)
    {
        $key = 'findAllOnlySlug-zdozkdozk';
        if (!$this->cache->has($key)) {
            $this->cache->set($key, 0);
        } else {
            $nbr = $this->cache->get($key);
            $nbr++;
            $this->cache->set($key, $nbr);
        }

        $qb = $this->createQueryBuilder('l')
            ->select('l.slug')
            ->getQuery()
            ->setMaxResults($max)
            ->setFirstResult($this->cache->get($key) * $max)
            ->getResult();

        if (empty($qb) || $qb === null){
            $this->cache->delete($key);
        }

        return $qb;
    }
}
