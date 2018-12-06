<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Symfony\Component\Yaml\Tests\A;

class ActivityRepository extends NestedTreeRepository
{
    /**
     * Liste of activities
     * @var $activities array
     */
    private $activities;
    private $activity;

    public function __construct(ManagerRegistry $registry)
    {
        $entityClass = Activity::class;

        $manager = $registry->getManagerForClass($entityClass);

        parent::__construct($manager, $manager->getClassMetadata($entityClass));
    }

    /**
     * @param Collection $clientActivities
     * @return Activity
     */
    public function getAllActivitiesOnlyWithThoseChildren(Collection $clientActivities)
    {
        $activity = new Activity();
        $activities = $this->findBy([
            'parent' => null
        ]);
        foreach ($activities as $child) {
            $activity->addChildren($child);
        }
        dd($clientActivities->toArray());


        return $activity;

    }

    /**
     * @param string $name
     * @param string $locale
     * @return int|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getActivityName(string $name, string $locale): ?int
    {
        $qb = $this->createQueryBuilder('activity');

        $qb->innerJoin('activity.translation', 't')
            ->where('t.' . $locale . 'Slug = \'' . $name . '\'');

        $activity = $qb
            ->getQuery()
            ->getOneOrNullResult();

        /** @var $activity Activity */
        if ($activity !== null) return $activity->getId();
        return null;
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
            ->innerJoin('a.translation', 't')
            ->getQuery()
            ->getResult();

        return array_unique($qb);
    }

    /**
     * @param Collection $activities
     * @return Activity
     */
    public function activitiesTreeClient(Collection $activities): Activity
    {
        $this->activities = null;
        foreach ($activities as $activity) {
            $this->iteratorTree($activity);
        }

        dump($this->activities);

        return new Activity();
    }

    public function iteratorTree($activity)
    {
        /**
         * @var $activity Activity
         */
        if (!isset($this->activities[$activity->getLevel()])){
            if (!isset($this->activities[$activity->getLevel()][$activity->getId()])){
                $this->activities[$activity->getLevel()][$activity->getId()][] = $activity;
            }
        }
        if ($activity->getParent()){
            $this->iterator($activity->getParent());
        }
    }

    public function getAllChildren($idElement)
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->where('a.id = ' . $idElement)
            ->innerJoin('a.translation', 't')
            ->getQuery()
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
}
