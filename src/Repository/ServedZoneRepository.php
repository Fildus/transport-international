<?php

namespace App\Repository;

use App\Entity\ServedZone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ServedZone|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServedZone|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServedZone[]    findAll()
 * @method ServedZone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServedZoneRepository extends ServiceEntityRepository
{
    /**
     * Liste of servedZones
     * @var $servedZones array
     */
    private $servedZones;

    /**
     * @var
     */
    private $servedZone;
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(RegistryInterface $registry, CacheInterface $cache)
    {
        parent::__construct($registry, ServedZone::class);
        $this->cache = $cache;
    }

    /**
     * @return mixed
     */
    public function findByWithTranslation()
    {
        $qb = $this
            ->createQueryBuilder('s')
            ->leftJoin('s.children', 'children')
            ->leftJoin('s.translation', 't')
            ->addSelect('t')
            ->where('s.level = 0')
            ->getQuery()
            ->useResultCache(true)
            ->getResult();

        return $qb;
    }

    /**
     * @param string $name
     * @param string $locale
     * @return int|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getServedZoneByDepartment(string $name, string $locale): ?int
    {
        $qb = $this->createQueryBuilder('s');

        $qb->innerJoin('s.translation', 't')
            ->where('t.' . $locale . 'Slug = \'' . $name . '\'');

        $servedZone = $qb
            ->getQuery()
//            ->useResultCache(true)
            ->getOneOrNullResult();

        /** @var $servedZone ServedZone */
        if ($servedZone !== null) return $servedZone->getId();
        return null;
    }

    /**
     * @param ServedZone $servedZone
     * @return array|null
     */
    public function getServedZoneByCountry(ServedZone $servedZone): ?array
    {
        /**
         * @var $servedZone ServedZone
         */
        if ($servedZone !== null && !$servedZone->getChildren()->isEmpty()) {
            $this->iterator($servedZone);
        }


        if ($this->servedZones !== null) return $this->servedZones;
        return null;
    }

    public function iterator(ServedZone $servedZone)
    {
        /**
         * @var $servedZone ServedZone
         * @var $child ServedZone
         */
        if (!$servedZone->getChildren()->isEmpty()) {
            foreach ($servedZone->getChildren() as $child) {
                $this->iterator($child);
            }
        } else {
            $this->servedZones[$servedZone->getId()] = $servedZone->getId();
        }
    }

    /**
     * @param string $department
     * @param string $locale
     * @return ServedZone|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getServedZoneNameByDepartment(string $department, string $locale): ?ServedZone
    {
        $qb = $this->createQueryBuilder('s');

        $qb->innerJoin('s.translation', 't')
            ->where('t.' . $locale . ' = \'' . $department . '\'');

        $servedZone = $qb
            ->getQuery()
            ->useResultCache(true)
            ->getOneOrNullResult();

        /** @var $servedZone ServedZone */
        if ($servedZone !== null) return $servedZone;
        return null;
    }

    public function startWith(string $department, string $locale): ?array
    {
        $qb = $this->createQueryBuilder('s');

        $qb
            ->innerJoin('s.translation', 't')
            ->andWhere('s.country IS NULL')
            ->andWhere('s.region IS NULL');

        $choices = $qb
            ->getQuery()
            ->useResultCache(true)
            ->getResult();

        $returnArray = [];

        foreach ($choices as $choice) {
            $trad = $choice->getTranslation()->__get($locale);
            if (preg_match('/' . strtolower($department) . '/', strtolower($trad))) {
                $returnArray[] = (string)$choice->getTranslation()->__get($locale);
            }
        }

        return $returnArray;
    }

    public function getAllCountry()
    {
        $qb = $this
            ->createQueryBuilder('s')
            ->where('s.level = 0')
            ->leftJoin('s.translation', 't')
            ->addSelect('t')
            ->getQuery()
            ->useResultCache(true)
            ->getResult();

        return $qb;
    }

    /**
     * @param $country
     * @param $locale
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getDeptByCountry($country, $locale)
    {
        $country = $this->createQueryBuilder('s')
            ->where('s.id =' . (int)$country)
            ->getQuery()
            ->useResultCache(true)
            ->getOneOrNullResult();

        $this->iteratorGetDeptByCountry($country);

        $redefinedIndex = [];

        foreach ($this->servedZones as $k => $v) {
            $redefinedIndex[$v['department']] = $k;
        }

        ksort($redefinedIndex);

        $return = [];

        $i = 0;
        foreach ($redefinedIndex as $k => $v){
            $return[$i] = $this->servedZones[$v];
            $i++;
        }

        return $return;
    }

    /**
     * @param ServedZone $servedZone
     */
    public function iteratorGetDeptByCountry(ServedZone $servedZone)
    {
        /**
         * @var $servedZone ServedZone
         * @var $child ServedZone
         */
        if (!$servedZone->getChildren()->isEmpty()) {
            foreach ($servedZone->getChildren() as $child) {
                $this->iteratorGetDeptByCountry($child);
            }
        } else {
            if ($servedZone->getType() === ServedZone::DEPARTMENT)
                $this->servedZones[] = ['id' => $servedZone->getId(), 'department' => $servedZone->getTranslation()->__toString()];
        }
    }

    /**
     * @param string $toCountry
     * @param string $toDept
     * @param $locale
     * @return array|null
     */
    public function getTowServedZoneFromPathAndName(?string $toCountry, ?string $toDept, $locale)
    {
        if ($toCountry !== null) {
            $qbA = $this
                ->createQueryBuilder('s')
                ->innerJoin('s.translation', 't')
                ->innerJoin('s.children', 'c')
                ->where('t.' . $locale . 'Slug  = \'' . $toCountry . '\'')
                ->getQuery()
                ->useResultCache(true)
                ->getResult();

            if ($toDept !== null) {
                /** @var $servedZone ServedZone */
                foreach ($qbA as $servedZone) {
                    $this->iteratorAllchildren($servedZone, $toDept);
                    if (!empty($this->servedZone)) {
                        return ['Country' => $servedZone, 'Dept' => $this->servedZone];
                    }
                }
            } else if (is_iterable($qbA)) {
                return ['Country' => $qbA[0]] ?? null;
            }
            return ['Country' => $qbA] ?? null;
        }
        return ['Country' => null, 'Dept' => null];
    }

    public function iteratorAllchildren(ServedZone $servedZone, $toDept)
    {
        /**
         * @var $servedZone ServedZone
         * @var $child ServedZone
         */
        if ($servedZone->getTranslation()->getSlug() === $toDept) {
            $this->servedZone = $servedZone;
        } elseif (!$servedZone->getChildren()->isEmpty()) {
            foreach ($servedZone->getChildren() as $child) {
                $this->iteratorAllchildren($child, $toDept);
            }
        }
    }


    /**
     * @param $el
     * @return array|null
     */
    public function findByLocation($el): ?array
    {

        $returnArray = [];

        /**
         * @var $r ServedZone
         */
        foreach (
            $this->createQueryBuilder('s')
                ->innerJoin('s.translation', 't')
                ->andWhere('s.country is null')
                ->andWhere('s.region is null')
                ->getQuery()
                ->useResultCache(true)
                ->getResult()
            as $r) {
            if (preg_match('/' . strtolower($el) . '/', strtolower($r->getTranslation()))) {
                if (!in_array($r->getTranslation(), $returnArray)) {
                    $returnArray[] = (string)$r->getTranslation();
                }
            }
        }

        return $returnArray;
    }

    public function getCountryAndRegionByIdAndTranslation(?ServedZone $servedZone)
    {
        $qb = $this
            ->createQueryBuilder('s')
            ->where('s.department is null')
            ->join('s.translation', 't')
            ->getQuery()
            ->useResultCache(true)
            ->getResult();

        $returnArray = [];

        if ($servedZone !== null) {
            $returnArray[$servedZone->getTranslation()->__toString()] = $servedZone->getId();
            $returnArray['Pas de parent'] = null;
        }else{
            $returnArray['Pas de parent'] = null;

        }

        /** @var $item ServedZone */
        foreach ($qb as $item) {
            $returnArray[$item->getRegion() ? 'country' : 'region'][$item->getTranslation()->__toString()] = $item->getId();
        }

        return $returnArray;
    }

    /**
     * @param int $parentId
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function allChildrens(int $parentId)
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.id =' . $parentId)
            ->getQuery()
            ->getOneOrNullResult();

        $this->iteratorAllChildrenByLevel($qb);
        return $this->servedZones;
    }

    public function iteratorAllChildrenByLevel(ServedZone $servedZone)
    {
        /**
         * @var $servedZone ServedZone
         * @var $child ServedZone
         */
        foreach ($servedZone->getChildren() as $child) {
            $this->iteratorAllChildrenByLevel($child);
        }
        if ($servedZone->getChildren()->isEmpty()) {
            $this->servedZones[] = $servedZone;
        }

    }
}
