<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query\Expr;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Psr\SimpleCache\CacheInterface;

class ActivityRepository extends ServiceEntityRepository
{
    /**
     * Liste of activities
     * @var $activities array
     */
    private $activities;

    /**
     * @var $activity
     */
    private $activity;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(RegistryInterface $registry, CacheInterface $cache)
    {
        parent::__construct($registry, Activity::class);

        $this->cache = $cache;
    }

    public function findByWithTranslation()
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->leftJoin('a.children', 'children')
            ->where('a.level = 0')
            ->getQuery()
            ->useResultCache(true)
            ->getResult();

        return $qb;
    }

    /**
     * @param Activity $activity
     * @return array|null
     */
    public function getActivityPath(Activity $activity): ?array
    {
        /**
         * @var $activity Activity
         */
        if ($activity !== null && !$activity->getChildren()->isEmpty()) {
            $this->iterator($activity);
        }

        if ($this->activities !== null) return $this->activities;
        return null;
    }

    public function iterator($activity)
    {
        /**
         * @var $activity Activity
         * @var $child Activity
         */
        if (!$activity->getChildren()->isEmpty()) {
            foreach ($activity->getChildren() as $child) {
                $this->iterator($child);
            }
        } else {
            $this->activities[$activity->getId()] = $activity->getId();
        }
    }

    public function getActivities($rootElements)
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->where((new Expr())->in(
                'a.path', $rootElements
            ))
            ->leftJoin('a.translation', 't')
            ->addSelect('t')
            ->getQuery()
            ->useResultCache(true)
            ->getResult();

        return array_unique($qb);
    }

    public function getAllChildren($idElement)
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->where('a.id = ' . (int)$idElement)
            ->innerJoin('a.translation', 't')
            ->getQuery()
            ->useResultCache(true)
            ->getResult();

        $this->iteratorChildren($qb[0]);

        return $this->activities;
    }

    public function iteratorChildren($activity)
    {
        /**
         * @var $activity Activity
         * @var $child Activity
         */
        if (!$activity->getChildren()->isEmpty()) {
            foreach ($activity->getChildren() as $child) {
                $this->iteratorChildren($child);
            }
        } else {
            $this->activities[] = [
                'id' => $activity->getId(),
                'activity' => $activity->getTranslation()->__tostring()
            ];
        }
    }

    /**
     * @param $typeA string charter|transportOgGoods
     * @param $typeB string byContainer|charterFull
     * @param $locale
     * @return array|null
     */
    public function getTowTypesFromPathAndName(?string $typeA, ?string $typeB, $locale)
    {
        if ($typeA !== null) {
            $qbA = $this
                ->createQueryBuilder('a')
                ->innerJoin('a.translation', 't')
                ->where('t.' . $locale . 'Slug  = \'' . $typeA . '\'')
                ->getQuery()
                ->useResultCache(true)
                ->getResult();

            if ($typeB !== null && $qbA !== null) {
                /** @var $activity Activity */
                foreach ($qbA as $activity) {
                    $this->iteratorAllchildren($activity, $typeB);
                    if (!empty($this->activity)) {
                        return ['typeA' => $activity, 'typeB' => $this->activity];
                    }
                }
            } else if ($typeA !== null) {
                return ['typeA' => $qbA[0], 'typeB' => null];
            }
        }
        return ['typeA' => null, 'typeB' => null];
    }

    public function iteratorAllchildren(Activity $activity, $typeB)
    {
        /**
         * @var $activity Activity
         * @var $child Activity
         */
        if ($activity->getTranslation()->getSlug() === $typeB) {
            $this->activity = $activity;
        } elseif (!$activity->getChildren()->isEmpty()) {
            foreach ($activity->getChildren() as $child) {
                $this->iteratorAllchildren($child, $typeB);
            }
        }
    }

    /**
     * @param Activity|null $activity
     * @return array
     */
    public function getPathById(?Activity $activity)
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->where('a.type = 1')
            ->join('a.translation', 't')
            ->getQuery()
            ->useResultCache(true)
            ->getResult();

        $returnArray = [];

        if ($activity !== null) {
            $returnArray[$activity->getTranslation()->__toString()] = $activity->getId();
            $returnArray['Pas de parent'] = null;
        } else {
            $returnArray['Pas de parent'] = null;

        }

        /** @var $item Activity */
        foreach ($qb as $item) {
            $returnArray[$item->getPath() ? 'activité' : 'prestation'][$item->getTranslation()->__toString()] = $item->getId();
        }

        return $returnArray;
    }
}
