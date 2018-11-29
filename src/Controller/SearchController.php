<?php

namespace App\Controller;


use App\Repository\ActivityRepository;
use App\Repository\ServedZoneRepository;
use App\Repository\TranslationRepository;
use App\Services\Locale;
use App\Repository\ClientRepository;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $this->locale = $locale->getLocalematched();
        $this->clientRepository = $clientRepository;
        $this->translationRepository = $translationRepository;
        $this->activityRepository = $activityRepository;
        $this->servedZoneRepository = $servedZoneRepository;
        $this->cache = $cache;
    }

    /**
     * @Route({
     *      "default": "/search/page-{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}",
     *      "fr" : "/recherche/page-{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}",
     *      "en" : "/search-en/page-{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}",
     *      "es" : "/search-es/page-{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}",
     *      "de" : "/search-de/page-{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}",
     *      "it" : "/search-it/page-{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}",
     *      "pt" : "/search-pt/page-{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}",
     *      "be" : "/search-be/page-{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}",
     *      "ad" : "/search-ad/page-{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}",
     *      "ro" : "/search-ro/page-{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}",
     *      "ma" : "/search-ma/page-{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}",
     *      "ci" : "/search-ci/page-{page}/{typeA}/{typeB}/{toCountry}/{toDept}/{fromCountry}/{fromDept}",
     * }, name="_search", defaults={"typeA": null, "typeB": null, "toCountry": null, "toDept": null, "fromCountry": null, "fromDept": null})
     * @param $page
     * @param $typeA
     * @param $typeB
     * @param $toCountry
     * @param $toDept
     * @param $fromCountry
     * @param $fromDept
     * @return Response
     */
    public function search($page, $typeA, $typeB, $toCountry, $toDept, $fromCountry, $fromDept): Response
    {
        $null = ['tous-pays', 'tous-departements', 'toutes-catégories', 'toutes-activités'];

        $typeA = in_array($typeA, $null) ? null : $typeA;
        $typeB = in_array($typeB, $null) ? null : $typeB;
        $toCountry = in_array($toCountry, $null) ? null : $toCountry;
        $toDept = in_array($toDept, $null) ? null : $toDept;
        $fromCountry = in_array($fromCountry, $null) ? null : $fromCountry;
        $fromDept = in_array($fromDept, $null) ? null : $fromDept;

        $activities = $this->activityRepository->getTowTypesFromPathAndName($typeA, $typeB, $this->locale);
        $toServedZones = $this->servedZoneRepository->getTowServedZoneFromPathAndName($toCountry, $toDept, $this->locale);
        $fromServedZones = $this->servedZoneRepository->getTowServedZoneFromPathAndName($fromCountry, $fromDept, $this->locale);

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

        return new Response($this->renderView('search.html.twig', [
            'clients' => $clients['clients'],
            'count' => $clients['count'],
            'page' => $page,
            'countries' => $this->servedZoneRepository->getAllCountry(),
            'activities' => $this->activityRepository->getActivities([
                'charter', 'passengerTransport', 'mover', 'storage', 'rentWithDriver', 'logistic', 'taxi', 'transportOfGoods'
            ]),
            'dataInjection' => json_encode($dataInjection)
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
     * @return Response
     */
    public function getUrl(Request $request)
    {
        $page = 1;
        dump($request->get('typeB'));

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
                $typeA = 'toutes-catégories';
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
                $typeB = 'toutes-activités';
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

        return $this->redirectToRoute('_search.'.$this->locale, [
            'typeA' => $typeA ?? 'toutes-catégories',
            'typeB' => $typeB ?? 'toutes-activités',
            'toCountry' => $toCountry ?? 'tous-pays',
            'toDept' => $toDept ?? 'tous-departements',
            'fromCountry' => $fromCountry ?? 'tous-pays',
            'fromDept' => $fromDept ?? 'tous-departements',
            'page' => $page
        ]);
    }

}