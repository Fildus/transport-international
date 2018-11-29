<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use App\Services\Locale;
use App\Repository\ServedZoneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AutocompleteController extends AbstractController
{

    /**
     * @var Locale
     */
    private $locale;

    public function __construct(Locale $locale)
    {
        $this->locale = $locale->setLocale()->getLocalematched();
    }

    /**
     * @Route("/autocomplete", name="_autocomplete_department")
     * @param ServedZoneRepository $servedZoneRepository
     * @param ActivityRepository $activityRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function autocompleteDepartment(ServedZoneRepository $servedZoneRepository, ActivityRepository $activityRepository, Request $request): JsonResponse
    {
        if ($req = $request->get('q')) {
            $choices = $servedZoneRepository->startWith($req, $this->locale);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('fromCountry')) {
            $choices = $servedZoneRepository->getDeptByCountry($req, $this->locale);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('toCountry')) {
            $choices = $servedZoneRepository->getDeptByCountry($req, $this->locale);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('typeA')) {
            $choices = $activityRepository->getAllChildren($req);
            return new JsonResponse($choices);
        }
    }
}
