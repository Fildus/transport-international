<?php

namespace App\Controller\Front;


use App\Repository\ActivityRepository;
use App\Repository\ServedZoneRepository;
use App\Repository\TranslationRepository;
use App\Services\Locale;
use App\Repository\ClientRepository;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @var Locale
     */
    private $locale;

    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var TranslationRepository
     */
    private $translationRepository;

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

    /**
     * SearchController constructor.
     *
     * @param Locale                $locale
     * @param ClientRepository      $clientRepository
     * @param ActivityRepository    $activityRepository
     * @param TranslationRepository $translationRepository
     * @param ServedZoneRepository  $servedZoneRepository
     * @param CacheInterface        $cache
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(
        Locale $locale,
        ClientRepository $clientRepository,
        ActivityRepository $activityRepository,
        TranslationRepository $translationRepository,
        ServedZoneRepository $servedZoneRepository,
        CacheInterface $cache
    )
    {
        $locale->setLocale();
        $this->locale = $locale;
        $this->clientRepository = $clientRepository;
        $this->translationRepository = $translationRepository;
        $this->activityRepository = $activityRepository;
        $this->servedZoneRepository = $servedZoneRepository;
        $this->cache = $cache;
    }

    /**
     * @Route({
     *      "default": "/search/{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}/{clientsIds}",
     *      "fr" : "/recherche/{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}/{clientsIds}",
     *      "en" : "/search-en/{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}/{clientsIds}",
     *      "es" : "/search-es/{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}/{clientsIds}",
     *      "de" : "/search-de/{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}/{clientsIds}",
     *      "it" : "/search-it/{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}/{clientsIds}",
     *      "pt" : "/search-pt/{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}/{clientsIds}",
     *      "be" : "/search-be/{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}/{clientsIds}",
     *      "ad" : "/search-ad/{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}/{clientsIds}",
     *      "ro" : "/search-ro/{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}/{clientsIds}",
     *      "ma" : "/search-ma/{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}/{clientsIds}",
     *      "ci" : "/search-ci/{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}/{clientsIds}",
     * }, name="_search", defaults={"typeA": null, "typeB": null, "toCountry": null, "toDept": null, "fromCountry":
     * null, "fromDept": null, "page":null, "clientsIds":null})
     * @param $page
     * @param $typeA
     * @param $typeB
     * @param $toCountry
     * @param $toDept
     * @param $fromCountry
     * @param $fromDept
     *
     * @param $clientsIds
     *
     * @return Response
     */
    public function search($page, $typeA, $typeB, $toCountry, $toDept, $fromCountry, $fromDept, $clientsIds): Response
    {
        $null = ['tous-pays', 'tous-departements', 'toutes-categories', 'toutes-activites'];

        $typeA = in_array($typeA, $null) ? null : $typeA;
        $typeB = in_array($typeB, $null) ? null : $typeB;
        $toCountry = in_array($toCountry, $null) ? null : $toCountry;
        $toDept = in_array($toDept, $null) ? null : $toDept;
        $fromCountry = in_array($fromCountry, $null) ? null : $fromCountry;
        $fromDept = in_array($fromDept, $null) ? null : $fromDept;

        $activities = $this->activityRepository->getTowTypesFromPathAndName($typeA, $typeB, $this->locale->getLocalematched());
        $toServedZones = $this->servedZoneRepository->getTowServedZoneFromPathAndName($toCountry, $toDept, $this->locale->getLocalematched());
        $fromServedZones = $this->servedZoneRepository->getTowServedZoneFromPathAndName($fromCountry, $fromDept, $this->locale->getLocalematched());

        $clients = $this->clientRepository->getClientFrom_activity_servedZone(
            $activities['typeA'] ?? null,
            $activities['typeB'] ?? null,
            $toServedZones['Country'] ?? null,
            $toServedZones['Dept'] ?? null,
            $fromServedZones['Country'] ?? null,
            $fromServedZones['Dept'] ?? null,
            $page
        );

        $dataInjection = [
            'fromCountry' => isset($fromServedZones['Country']) ? $fromServedZones['Country']->getId() : null,
            'fromDept' => isset($fromServedZones['Dept']) ? $fromServedZones['Dept']->getId() : null,
            'toCountry' => isset($toServedZones['Country']) ? $toServedZones['Country']->getId() : null,
            'toDept' => isset($toServedZones['Dept']) ? $toServedZones['Dept']->getId() : null,
            'typeA' => isset($activities['typeA']) ? $activities['typeA']->getId() : null,
            'typeB' => isset($activities['typeB']) ? $activities['typeB']->getId() : null
        ];

        return new Response($this->renderView('pages/search.html.twig', [
            'clients' => $clients['clients'],
            'count' => $clients['count'],
            'page' => $page,
            'countries' => $this->servedZoneRepository->getAllCountry(),
            'activities' => $this->activityRepository->getActivities([
                'charter', 'passengerTransport', 'mover', 'storage', 'rentWithDriver', 'logistic', 'taxi', 'transportOfGoods'
            ]),
            'dataInjection' => json_encode($dataInjection),
            'data' => $dataInjection,
            'domain' => $this->locale->getDomain()
        ]));
    }

    /**
     * @Route({
     *      "default": "/search-by-company/{page}/{clientsIds}",
     *      "fr" : "/search-by-company-fr/{page}/{clientsIds}",
     *      "en" : "/search-by-company-en/{page}/{clientsIds}",
     *      "es" : "/search-by-company-es/{page}/{clientsIds}",
     *      "de" : "/search-by-company-de/{page}/{clientsIds}",
     *      "it" : "/search-by-company-it/{page}/{clientsIds}",
     *      "pt" : "/search-by-company-pt/{page}/{clientsIds}",
     *      "be" : "/search-by-company-be/{page}/{clientsIds}",
     *      "ad" : "/search-by-company-ad/{page}/{clientsIds}",
     *      "ro" : "/search-by-company-ro/{page}/{clientsIds}",
     *      "ma" : "/search-by-company-ma/{page}/{clientsIds}",
     *      "ci" : "/search-by-company-ci/{page}/{clientsIds}",
     * }, name="_search_company", defaults={"clientsIds":null, "page":1})
     * @return Response
     */
    public function searchByCompany($page, $clientsIds)
    {
        $clientsIds = explode('-', $clientsIds);
        $clientsByIds = $this->clientRepository->findById($clientsIds);

        return new Response($this->renderView('pages/search.html.twig', [
            'clients' => $clientsByIds,
            'count' => count($clientsByIds),
            'page' => $page,
            'countries' => $this->servedZoneRepository->getAllCountry(),
            'activities' => $this->activityRepository->getActivities([
                'charter', 'passengerTransport', 'mover', 'storage', 'rentWithDriver', 'logistic', 'taxi', 'transportOfGoods'
            ]),
            'dataInjection' => json_encode(null),
            'data' => null,
            'domain' => $this->locale->getDomain()
        ]));
    }

    /**
     * @Route({
     *      "default": "/getUrl",
     *      "fr" : "/getUrl",
     *      "en" : "/getUrl",
     *      "es" : "/getUrl",
     *      "de" : "/getUrl",
     *      "it" : "/getUrl",
     *      "pt" : "/getUrl",
     *      "be" : "/getUrl",
     *      "ad" : "/getUrl",
     *      "ro" : "/getUrl",
     *      "ma" : "/getUrl",
     *      "ci" : "/getUrl",
     * }, name="_search_geturl")
     * @param Request $request
     *
     * @return Response
     */
    public function getUrl(Request $request)
    {
        if ($typeA = $request->get('typeA')) {
            if ($typeA !== 'null' && $typeA !== 'all') {
                $typeA = $this->activityRepository->findOneBy([
                        'id' => (int)$typeA
                    ]) ?? null;
                if ($typeA !== null) {
                    $typeA = $typeA->getTranslation()->getSlug();
                } else {
                    $typeA = null;
                }
            } else {
                $typeA = 'toutes-categories';
            }
        }

        if ($typeB = $request->get('typeB')) {
            if ($typeB !== 'null' && $typeB !== 'all') {
                $typeB = $this->activityRepository->findOneBy([
                        'id' => (int)$typeB
                    ]) ?? null;
                if ($typeB !== null) {
                    $typeB = $typeB->getTranslation()->getSlug();
                } else {
                    $typeB = null;
                }
            } else {
                $typeB = 'toutes-activites';
            }
        }

        if ($toCountry = $request->get('toCountry')) {
            if ($toCountry !== 'null' && $toCountry !== 'all') {
                $toCountry = $this->servedZoneRepository->findOneBy([
                        'id' => (int)$toCountry
                    ]) ?? null;
                if ($toCountry !== null) {
                    $toCountry = $toCountry->getTranslation()->getSlug();
                } else {
                    $toCountry = null;
                }
            } else {
                $toCountry = 'tous-pays';
            }
        }

        if ($toDept = $request->get('toDept')) {
            if ($toDept !== 'null' && $toDept !== 'all') {
                $toDept = $this->servedZoneRepository->findOneBy([
                        'id' => (int)$toDept
                    ]) ?? null;
                if ($toDept !== null) {
                    $toDept = $toDept->getTranslation()->getSlug();
                } else {
                    $toDept = null;
                }
            } else {
                $toDept = 'tous-departements';
            }
        }

        if ($fromCountry = $request->get('fromCountry')) {
            if ($fromCountry !== 'null' && $fromCountry !== 'all') {
                $fromCountry = $this->servedZoneRepository->findOneBy([
                        'id' => (int)$fromCountry
                    ]) ?? null;
                if ($fromCountry !== null) {
                    $fromCountry = $fromCountry->getTranslation()->getSlug();
                } else {
                    $fromCountry = null;
                }
            } else {
                $fromCountry = 'tous-pays';
            }
        }

        if ($fromDept = $request->get('fromDepartment')) {
            if ($fromDept !== 'null' && $fromDept !== 'all') {
                $fromDept = $this->servedZoneRepository->findOneBy([
                        'id' => (int)$fromDept
                    ]) ?? null;
                if ($fromDept !== null) {
                    $fromDept = $fromDept->getTranslation()->getSlug();
                } else {
                    $fromDept = null;
                }
            } else {
                $fromDept = 'tous-departements';
            }
        }


        if ($socialRaison = $request->get('sr')) {
            $clientsIds = $this->clientRepository->findOneByCompanyName($socialRaison);
            return $this->redirectToRoute('_search_company', ['page' => 1, 'clientsIds' => $clientsIds]);
        }

        return $this->redirectToRoute('_search.' . $this->locale->getLocalematched(), [
            'typeA' => $typeA ?? 'toutes-catégories',
            'typeB' => $typeB ?? 'toutes-activités',
            'toCountry' => $toCountry ?? 'tous-pays',
            'toDept' => $toDept ?? 'tous-departements',
            'fromCountry' => $fromCountry ?? 'tous-pays',
            'fromDept' => $fromDept ?? 'tous-departements',
            'page' => 1
        ]);
    }

}