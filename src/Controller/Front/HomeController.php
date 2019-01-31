<?php

namespace App\Controller\Front;

use App\Repository\LegalInformationRepository;
use App\Services\Locale;
use Psr\SimpleCache\CacheInterface;
use App\Repository\ActivityRepository;
use App\Repository\ServedZoneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
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
     * HomeController constructor.
     *
     * @param ActivityRepository   $activityRepository
     * @param ServedZoneRepository $servedZoneRepository
     *
     */
    public function __construct(ActivityRepository $activityRepository, ServedZoneRepository $servedZoneRepository)
    {
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
     * @param CacheInterface   $cache
     *
     * @param Request          $request
     *
     * @param SessionInterface $session
     *
     * @return Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function home(CacheInterface $cache, Request $request, SessionInterface $session, LegalInformationRepository $legalInformationRepository): Response
    {
        $key = 'home-z4d4zd45-' . $request->getLocale();
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
            'domain' => $session->get('domain')
        ]));
    }
}