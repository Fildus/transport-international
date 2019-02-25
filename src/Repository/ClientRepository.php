<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\Client;
use App\Entity\Search\ClientSearch;
use App\Entity\ServedZone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\Expr;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method findById(array $clientsIds)
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

    public function __construct(
        RegistryInterface $registry,
        ActivityRepository $activityRepository,
        ServedZoneRepository $servedZoneRepository,
        CacheInterface $cache
    )
    {
        parent::__construct($registry, Client::class);
        $this->activityRepository = $activityRepository;
        $this->servedZoneRepository = $servedZoneRepository;
        $this->cache = $cache;
    }

    /**
     * @param int $nbr
     *
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
            ->innerJoin('c.contract', 'contract')
            ->innerJoin('c.contact', 'contact')
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
     *
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
            ->innerJoin('c.user', 'user')
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

        if ($search->getContract() !== null) {
            if ((int)$search->getContract() === 1) {
                $qb->andWhere('c.contract is not empty');
            }
            if ((int)$search->getContract() === 0) {
                $qb->andWhere('c.contract is empty');
            }
        }

        if ($search->isHaveEmail() !== null) {
            if ((int)$search->isHaveEmail() === 1) {
                $qb->andWhere('user.username is not null');
                $qb->andWhere('user.username != \'\'');
            }
            if ((int)$search->isHaveEmail() === 0) {
                $qb->andWhere('user.username = \'\'');
            }
        }

        if ($search->getContract() !== null) {
            if ((int)$search->getContract() === 1) {
                $qb->andWhere('c.contract is not empty');
            }
            if ((int)$search->getContract() === 0) {
                $qb->andWhere('c.contract is empty');
            }
        }

        if ($search->isValidated() !== null) {
            $validated = $search->isValidated() ? 1 : 0;
            $qb->andWhere('c.validated =' . $validated);
        }

        $qb
            ->getQuery();

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
     *
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
                $qb->andWhere('a.id = ' . $typeB->getId());
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

        $qb = $qb
            ->andWhere('c.validated = 1');

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
     *
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
     *
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

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function findOneByCompanyName(string $name): ?string
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.legalInformation', 'l')
            ->where('l.companyName LIKE\'' . $name . '\'')
            ->orWhere('l.companyName LIKE\'' . $name . '\'')
            ->getQuery()
            ->getResult();

        $return = [];

        /**
         * @var $client Client
         */
        if ($qb !== null && !empty($qb)) {
            foreach ($qb as $client) {
                $return[] = $client->getId();
            }
            return implode('-', $return);
        }
        return null;
    }
}
