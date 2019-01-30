<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use App\Repository\ContactRepository;
use App\Repository\LegalInformationRepository;
use App\Repository\LocationRepository;
use App\Repository\UserRepository;
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

    /**
     * AutocompleteController constructor.
     *
     * @param Locale $locale
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(Locale $locale)
    {
        $this->locale = $locale->setLocale()->getLocalematched();
    }

    /**
     * @Route("/autocomplete", name="_autocomplete_department")
     * @param ServedZoneRepository $servedZoneRepository
     * @param ActivityRepository   $activityRepository
     * @param Request              $request
     *
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function autocompleteDepartment(
        ServedZoneRepository $servedZoneRepository,
        ActivityRepository $activityRepository,
        Request $request
    ): JsonResponse
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

    /**
     * @Route("/autocomplete/all", name="_autocomplete_all")
     * @param LegalInformationRepository $legalInformationRepository
     * @param LocationRepository         $locationRepository
     * @param ServedZoneRepository       $servedZoneRepository
     * @param ContactRepository          $contactRepository
     * @param UserRepository             $userRepository
     * @param Request                    $request
     *
     * @return JsonResponse
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function autocompleteAll(
        LegalInformationRepository $legalInformationRepository,
        LocationRepository $locationRepository,
        ServedZoneRepository $servedZoneRepository,
        ContactRepository $contactRepository,
        UserRepository $userRepository,
        Request $request): JsonResponse
    {
        if ($req = $request->get('corporateName')) {
            $choices = $legalInformationRepository->findByCorporateName($req);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('legalForm')) {
            $choices = $legalInformationRepository->findByLegalForm($req);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('companyName')) {
            $choices = $legalInformationRepository->findByCompanyName($req);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('siret')) {
            $choices = $legalInformationRepository->findBySiret($req);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('address')) {
            $choices = $locationRepository->findByAddress($req);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('postalCode')) {
            $choices = $locationRepository->findByPostalCode($req);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('city')) {
            $choices = $locationRepository->findByCity($req);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('location') ?? $request->get('location_location')) {
            $choices = $servedZoneRepository->findByLocation($req);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('phone')) {
            $choices = $contactRepository->findByPhone($req);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('fax')) {
            $choices = $contactRepository->findByFax($req);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('contact')) {
            $choices = $contactRepository->findByContact($req);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('webSite')) {
            $choices = $contactRepository->findByWebSite($req);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('location_location')) {
            $choices = $servedZoneRepository->findByLocation($req);
            return new JsonResponse($choices);
        }

        if ($req = $request->get('mail')) {
            $choices = $userRepository->findByMailLike($req);
            return new JsonResponse($choices);
        }

//        if ($req = $request->get('sr')) {
//            $choices = $legalInformationRepository->findByCompanyName($req);
//            return new JsonResponse($choices);
//        }

        return new JsonResponse(['faux' => 'don\'t work']);
    }
}
