<?php

namespace App\Controller\Front;

use App\Entity\Client;
use App\Repository\ActivityRepository;
use App\Repository\ClientRepository;
use App\Services\ArrayRecursion\RecursionInterface;
use App\Services\Locale;
use App\Services\Mailer;
use App\Services\Optico\Optico;
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
    /**
     * @var Locale
     */
    private $locale;

    /**
     * ProfessionalProfileController constructor.
     *
     * @param ClientRepository   $clientRepository
     * @param ActivityRepository $activityRepository
     * @param Locale             $locale
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(ClientRepository $clientRepository, ActivityRepository $activityRepository, Locale $locale)
    {
        $this->clientRepository = $clientRepository;
        $this->activityRepository = $activityRepository;
        $this->locale = $locale;
        $locale->setLocale();
    }

    /**
     * @Route({
     *      "default": "/professional/{cnSlug}",
     *      "fr" : "/professionnel/{cnSlug}",
     *      "en" : "/professional/{cnSlug}",
     *      "es" : "/profesional/{cnSlug}",
     *      "de" : "/professionel/{cnSlug}",
     *      "it" : "/professionale/{cnSlug}",
     *      "pt" : "/profissional/{cnSlug}",
     *      "be" : "/professionnel/{cnSlug}",
     *      "ad" : "/professionnel/{cnSlug}",
     *      "ro" : "/profesionist/{cnSlug}",
     *      "ma" : "/professionnel/{cnSlug}",
     *      "ci" : "/professionnel/{cnSlug}",
     * }, name="_professional_profile", defaults={"cnSlug":null})
     * @param                    $cnSlug
     * @param Mailer             $mailer
     * @param Request            $request
     * @param RecursionInterface $recursion
     *
     * @return Response .html.twig
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function profile($cnSlug, Mailer $mailer, Request $request, RecursionInterface $recursion): Response
    {
        $client = $this->clientRepository->getClientProfile($cnSlug);

        $form = $this->createFormBuilder()
            ->add('name', TextType::class, [
                'label' => 'professionalProfile.form.name'
            ])
            ->add('email', EmailType::class, [
                'label' => 'professionalProfile.form.email'
            ])
            ->add('message', TextType::class, [
                'label' => 'professionalProfile.form.message'
            ])
            ->add('antispam', TextType::class, [
                'label' => 'professionalProfile.form.antispam',
                'attr' => ['placeholder' => '30 + 1 ?']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ((int)$form->get('antispam')->getViewData() === 31) {
                $mailer->send($client->getUser()->getUsername(), $form->get('message')->getViewData());
            }
        }

        /**
         * @var $client Client
         */
        if ($client !== null) {
            if ($client->getContact() !== null && $client->getContact()->getPhone() !== null) {
                $phone = $client->getContact()->getPhone();
                $optico = new Optico('06f46a4bc4c2edd635373639de3c25b8');
                $optico->addPhone($phone);
                $optico->sendView();
                $optico->getViewId();
                $res = $optico->getTrackingPhoneNumber($phone);
            }
        }

        return new Response($this->renderView('pages/professionalProfile.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
            'domain' => $this->locale->getDomain(),
            'clients' => $this->clientRepository->findBy([], ['id' => 'DESC'], 12, 50),
            'number' => $res ?? null,
            'activities' => $recursion->run($client->getActivity()),
            'servedZones' => $recursion->run($client->getServedZone())
        ]));
    }
}
