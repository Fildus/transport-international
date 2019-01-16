<?php

namespace App\Controller\Front;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Services\Locale;
use Psr\SimpleCache\CacheInterface;
use App\Repository\ActivityRepository;
use App\Repository\ServedZoneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    /**
     * @var Locale
     */
    private $locale;
    /**
     * @var ActivityRepository
     */
    private $activityRepository;
    /**
     * @var ServedZoneRepository
     */
    private $servedZoneRepository;

    /**
     * HomeController constructor.
     * @param Locale $locale
     * @param ActivityRepository $activityRepository
     * @param ServedZoneRepository $servedZoneRepository
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(Locale $locale, ActivityRepository $activityRepository, ServedZoneRepository $servedZoneRepository)
    {
        $locale->setLocale();
        $this->locale = $locale;
        $this->activityRepository = $activityRepository;
        $this->servedZoneRepository = $servedZoneRepository;
    }

    /**
     * @Route({
     *     "fr" : "/",
     *     "en" : "/",
     *     "es" : "/",
     *     "ad" : "/",
     *     "be" : "/",
     *     "ci" : "/",
     *     "de" : "/",
     *     "it" : "/",
     *     "ma" : "/",
     *     "pt" : "/",
     *     "ro" : "/",
     * }, name="home")
     * @param CacheInterface $cache
     * @return Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function home(CacheInterface $cache): Response
    {
        $key = 'home-z4d4zd45-' . $this->locale->getLocalematched();
        if (!$cache->has($key)) {
            $cache->set($key, [
                'activities' => $this->activityRepository->getActivities([
                    'charter', 'passengerTransport', 'mover', 'storage', 'rentWithDriver', 'logistic', 'taxi', 'transportOfGoods'
                ]),
                'countries' => $this->servedZoneRepository->getAllCountry()
            ], 3600);
        }

        return new Response($this->renderView('pages/home.html.twig', [
            'activities' => $cache->get($key)['activities'],
            'countries' => $cache->get($key)['countries'],
            'domain' => $this->locale->getDomain()
        ]));
    }
}