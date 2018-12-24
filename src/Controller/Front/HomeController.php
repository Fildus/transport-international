<?php

namespace App\Controller\Front;

use App\Entity\Options;
use App\Repository\ActivityRepository;
use App\Repository\OptionsRepository;
use App\Repository\ServedZoneRepository;
use App\Services\Locale;
use App\Services\OptionsService;
use function MongoDB\BSON\fromJSON;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

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

    public function __construct(Locale $locale, ActivityRepository $activityRepository, ServedZoneRepository $servedZoneRepository)
    {
        $locale->setLocale();
        $this->locale = $locale->getLocalematched();
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
        $key = 'home-z4d4zd45' . $this->locale;
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
            'countries' => $cache->get($key)['countries']
        ]));
    }
}