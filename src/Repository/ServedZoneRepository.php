<?php

namespace App\Repository;

use App\Entity\ServedZone;
use Doctrine\Common\Persistence\ManagerRegistry;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class ServedZoneRepository extends NestedTreeRepository
{

    /**
     * Liste of servedZones
     * @var $servedZones array
     */
    private $servedZones;
    private $servedZone;

    public function __construct(ManagerRegistry $registry)
    {
        $entityClass = ServedZone::class;

        $manager = $registry->getManagerForClass($entityClass);

        parent::__construct($manager, $manager->getClassMetadata($entityClass));
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
            ->innerJoin('s.translation', 't')
            ->where('s.level = 0')
            ->getQuery()
            ->getResult();

        return $qb;
    }

    public function getDeptByCountry($country, $locale)
    {
        $qb = $this
            ->createQueryBuilder('s')
            ->innerJoin('s.translation', 't')
            ->where('s.root = ' . $country)
            ->andWhere('s.country is null')
            ->andWhere('s.region is null')
            ->getQuery()
            ->getResult();

        $arrayReturned = [];

        /** @var $servedZone ServedZone */
        foreach ($qb as $servedZone) {
            $arrayReturned[] = [
                'id' => $servedZone->getId(),
                'department' => $servedZone->getTranslation()->__get($locale)
            ];
        }

        return $arrayReturned;
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
}
