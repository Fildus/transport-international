<?php

namespace App\Controller;

use App\Entity\ServedZone;
use App\Repository\ActivityRepository;
use App\Repository\ServedZoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TryController extends AbstractController
{
    /**
     * @Route("/aaa")
     * @param ServedZoneRepository $servedZoneRepository
     * @param ActivityRepository $activityRepository
     * @return Response
     */
    public function index(ServedZoneRepository $servedZoneRepository, ActivityRepository $activityRepository) :Response
    {
        $sz = $servedZoneRepository->findBy(['type'=>ServedZone::COUNTRY]);
        $ar = $activityRepository->findBy(['level'=>0]);

        return $this->render('try.html.twig', compact('sz', 'ar'));
    }
}