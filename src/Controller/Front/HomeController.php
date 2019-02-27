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
     * @var Locale
     */
    private $locale;

    /**
     * HomeController constructor.
     *
     * @param ActivityRepository   $activityRepository
     * @param ServedZoneRepository $servedZoneRepository
     *
     * @param Locale               $locale
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(ActivityRepository $activityRepository, ServedZoneRepository $servedZoneRepository, Locale $locale)
    {
        $this->activityRepository = $activityRepository;
        $this->servedZoneRepository = $servedZoneRepository;
        $this->locale = $locale;
        $locale->setLocale();
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
     *
     * @return Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function home(CacheInterface $cache, Request $request): Response
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
            'domain' => $this->locale->getDomain(),
            'top' => $this->addToHome($request, 'top'),
            'bot' => $this->addToHome($request, 'bot'),
            'full' => $this->addToHome($request, 'full')
        ]));
    }

    /**
     * @param Request $request
     * @param string  $position
     *
     * @return false|string
     */
    public function addToHome(Request $request, string $position): ?string
    {
        $file = $request->getPathInfo() === '/' ? $file = '/' . $position . '.html' : $request->getPathInfo();
        $fullPath = '../pages/' . str_replace('www.', '', $request->getHttpHost()) . $file;
        if (file_exists($fullPath)) {
            return file_get_contents($fullPath);
        }
        return null;
    }
}