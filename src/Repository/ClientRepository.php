<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\Client;
use App\Entity\ServedZone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\SimpleCache\CacheInterface;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{

    /**
     * @var ActivityRepository
     */
    private $activityRepository;
    /**
     * @var ServedZoneRepository
     */
    private $servedZoneRepository;
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(CacheInterface $cache, RegistryInterface $registry, ActivityRepository $activityRepository, ServedZoneRepository $servedZoneRepository)
    {
        parent::__construct($registry, Client::class);
        $this->activityRepository = $activityRepository;
        $this->servedZoneRepository = $servedZoneRepository;
        $this->cache = $cache;
    }

    /**
     * @param Activity|null $typeA
     * @param Activity|null $typeB
     * @param ServedZone|null $toCountry
     * @param ServedZone|null $toDept
     * @param ServedZone|null $fromCountry
     * @param ServedZone|null $fromDept
     * @param int $page
     * @return array
     */
    public function getClientFrom_activity_servedZone(?Activity $typeA, ?Activity $typeB, ?ServedZone $toCountry, ?ServedZone $toDept, ?ServedZone $fromCountry, ?ServedZone $fromDept, int $page)
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->innerJoin('c.activity', 'a')
//            ->innerJoin('a.children', 'ac')
            ->innerJoin('c.servedZone', 's')
            ->innerJoin('c.location', 'l')
            ->innerJoin('l.location', 'll');

        if ($typeA) {
            if ($typeB) {
                $qb->where('a.id = ' . $typeB->getId());
            } else {
                $activitiesIds = $this->activityRepository->getActivityPath($typeA);
                $qb->where($qb->expr()->andX(
                    $qb->expr()->in('a.id', $activitiesIds)
                ));
            }
        }

        if ($toCountry) {
            if ($toDept) {
                $qb->andWhere('s.id = ' . $toDept->getId());
            } else {
                $servedZoneIds = $this->servedZoneRepository->getServedZoneByCountry($toCountry);
                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->in('s.id', $servedZoneIds)
                ));
            }
        }

        if ($fromCountry) {
            if ($fromDept) {
                $qb->andWhere('ll.id = ' . $fromDept->getId());
            } else {
                $servedZoneIds = $this->servedZoneRepository->getServedZoneByCountry($fromCountry);
                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->in('ll.id', $servedZoneIds)
                ));
            }
        }

        $qb
            ->distinct()
            ->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10);

        $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = true);

        $res = $paginator
            ->getQuery()
            ->getResult();

        return ['clients'=> $res, 'count'=> $paginator->count()];
    }

    /**
     * @param $cnSlug
     * @return int
     */
    public function getClientProfile($cnSlug)
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->innerJoin('c.legalInformation', 'l')
            ->where('l.slug = \'' . $cnSlug . '\'')
            ->getQuery()
            ->getResult();

        return $qb;
    }
}
