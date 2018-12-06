<?php

namespace App\Controller\Front;

use App\Entity\Client;
use App\Repository\ActivityRepository;
use App\Repository\ClientRepository;
use App\Services\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfessionalProfileController extends AbstractController
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var ActivityRepository
     */
    private $activityRepository;

    public function __construct(ClientRepository $clientRepository, ActivityRepository $activityRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->activityRepository = $activityRepository;
    }

    /**
     * @Route({
     *      "default": "/professional/{cnSlug}",
     *      "fr" : "/professional-fr/{cnSlug}",
     *      "en" : "/professional-en/{cnSlug}",
     *      "es" : "/professional-es/{cnSlug}",
     *      "de" : "/professional-de/{cnSlug}",
     *      "it" : "/professional-it/{cnSlug}",
     *      "pt" : "/professional-pt/{cnSlug}",
     *      "be" : "/professional-be/{cnSlug}",
     *      "ad" : "/professional-ad/{cnSlug}",
     *      "ro" : "/professional-ro/{cnSlug}",
     *      "ma" : "/professional-ma/{cnSlug}",
     *      "ci" : "/professional-ci/{cnSlug}",
     * }, name="_professional_profile", defaults={"cnSlug":null})
     * @param $cnSlug
     * @param Mailer $mailer
     * @param Request $request
     * @return Response .html.twig
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function profile($cnSlug, Mailer $mailer, Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('message', TextType::class)
            ->add('antispam', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ((int)$form->get('antispam')->getViewData() === 31) {
                $mailer->send($form->get('email')->getViewData(), $form->get('message')->getViewData());
            }
        }

        /** @var $client Client */
        $client = $this->clientRepository->getClientProfile($cnSlug);

        $activities = $client->getActivity();
        dump($activities);

        $test = $this->activityRepository->activitiesTreeClient($activities);

        return new Response($this->renderView('pages/professionalProfile.html.twig', [
            'client' => $client,
            'form' => $form->createView()
        ]));
    }
}
