<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\Client;
use App\Entity\Search\ClientSearch;
use App\Entity\ServedZone;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

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

    public function __construct(
        RegistryInterface $registry,
        ActivityRepository $activityRepository,
        ServedZoneRepository $servedZoneRepository
    )
    {
        parent::__construct($registry, Client::class);
        $this->activityRepository = $activityRepository;
        $this->servedZoneRepository = $servedZoneRepository;
    }

    /**
     * @param int $nbr
     * @return mixed
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function lastClients(int $nbr)
    {
        return $this->createQueryBuilder('c')
            ->addCriteria(Criteria::create()->orderBy(['id' => 'DESC']))
            ->innerJoin('c.equipment', 'e')
            ->innerJoin('c.user', 'u')
            ->innerJoin('c.managers', 'm')
            ->innerJoin('c.activity', 'a')
            ->innerJoin('c.legalInformation', 'l')
            ->andWhere('c.legalInformation != 0')
            ->andWhere('c.contact != 0')
            ->andWhere('c.location != 0')
            ->andWhere('c.coreBusiness != 0')
            ->andWhere('c.about != 0')
            ->andWhere('c.managers != 0')
            ->andWhere('c.user != 0')
            ->andWhere('c.equipment != 0')
            ->getQuery()
            ->setMaxResults($nbr)
            ->getResult();
    }

    /**
     * @param $search ClientSearch
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllClients(ClientSearch $search)
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.legalInformation', 'legalInformation')
            ->innerJoin('c.location', 'location')
            ->innerJoin('location.location', 'servedZone')
            ->innerJoin('servedZone.translation', 'servedZoneTranslation')
            ->innerJoin('c.contact', 'contact')
            ->orderBy('c.id', 'ASC');

        if ($search->getSiret() !== null) {
            $qb
                ->andWhere('legalInformation.siret LIKE :siret')
                ->setParameter('siret', $search->getSiret());
        }

        if ($search->getCorporateName() !== null) {
            $qb
                ->andWhere('legalInformation.corporateName LIKE :corporateName')
                ->setParameter('corporateName', $search->getCorporateName());
        }

        if ($search->getCompanyName() !== null) {
            $qb
                ->andWhere('legalInformation.companyName LIKE :companyName')
                ->setParameter('companyName', $search->getCompanyName());
        }

        if ($search->getLegalForm() !== null) {
            $qb
                ->andWhere('legalInformation.legalForm = :legalForm')
                ->setParameter('legalForm', $search->getLegalForm());
        }

        if ($search->getAddress() !== null) {
            $qb
                ->andWhere('location.address = :address')
                ->setParameter('address', $search->getAddress());
        }

        if ($search->getPostalCode() !== null) {
            $qb
                ->andWhere('location.postalCode = :postalCode')
                ->setParameter('postalCode', $search->getPostalCode());
        }

        if ($search->getCity() !== null) {
            $qb
                ->andWhere('location.city = :city')
                ->setParameter('city', $search->getCity());
        }

        if ($search->getLocation() !== null) {
            $qb
                ->andWhere('servedZoneTranslation.fr = :servedZoneTranslation')
                ->setParameter('servedZoneTranslation', $search->getLocation());
        }

        if ($search->getPhone() !== null) {
            $qb
                ->andWhere('contact.phone = :phone')
                ->setParameter('phone', $search->getPhone());
        }

        if ($search->getFax() !== null) {
            $qb
                ->andWhere('contact.fax = :fax')
                ->setParameter('fax', $search->getFax());
        }

        if ($search->getContact() !== null) {
            $qb
                ->andWhere('contact.contact = :contact')
                ->setParameter('contact', $search->getContact());
        }

        if ($search->getWebSite() !== null) {
            $qb
                ->andWhere('contact.webSite = :webSite')
                ->setParameter('webSite', $search->getWebSite());
        }

        $qb
            ->getQuery()
        ;

        return $qb;
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
    public function getClientFrom_activity_servedZone(?Activity $typeA, ?Activity $typeB, ?ServedZone $toCountry, ?ServedZone $toDept, ?ServedZone $fromCountry, ?ServedZone $fromDept, ?int $page)
    {
        $page === null ? $page = 1 : $page = (int)$page;

        $qb = $this
            ->createQueryBuilder('c')
            ->innerJoin('c.activity', 'a')
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
            ->useResultCache(true)
            ->getResult();

        return ['clients' => $res, 'count' => $paginator->count()];
    }

    /**
     * @param $cnSlug
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getClientProfile($cnSlug)
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->innerJoin('c.legalInformation', 'l')
            ->where('l.slug = \'' . $cnSlug . '\'')
            ->getQuery()
            ->useResultCache(true)
            ->getSingleResult();

        return $qb;
    }

    /**
     * @param $max
     * @return mixed
     */
    public function findLasts($max)
    {

        $qb = $this->createQueryBuilder('c')
            ->setMaxResults($max)
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->useResultCache(true);

        return $qb->getResult();
    }
}
