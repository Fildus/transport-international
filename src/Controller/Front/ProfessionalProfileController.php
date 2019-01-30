<?php

namespace App\Controller\Front;

use App\Entity\Client;
use App\Entity\ServedZone;
use App\Repository\ActivityRepository;
use App\Repository\ClientRepository;
use App\Services\ArrayRecursion\RecursionInterface;
use App\Services\Locale;
use App\Services\Mailer;
use App\Services\Optico\Optico;
use App\Services\Optico\OpticoService;
use Psr\SimpleCache\CacheInterface;
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
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var $clientCountry ServedZone
     */
    private $clientCountry;

    /**
     * ProfessionalProfileController constructor.
     *
     * @param ClientRepository   $clientRepository
     * @param ActivityRepository $activityRepository
     * @param Locale             $locale
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(ClientRepository $clientRepository, ActivityRepository $activityRepository, Locale $locale, CacheInterface $cache)
    {
        $this->clientRepository = $clientRepository;
        $this->activityRepository = $activityRepository;
        $this->locale = $locale;
        $locale->setLocale();
        $this->cache = $cache;
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
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function profile($cnSlug, Mailer $mailer, Request $request, RecursionInterface $recursion): Response
    {
        /**
         * @var $client Client
         */
        $client = $this->clientRepository->getClientProfile($cnSlug);
//        dd($client->getContact()->getPhone());
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

        $optico = new OpticoService();
        if ($request->getSession() && $request->getSession()->has('tryHaveNumber')) {
            $try = count($request->getSession()->get('tryHaveNumber'));
            if ($try > 10) {
                $number = $optico->getNormalNumber($client);
            } else {
                $number = $optico->getNumber($client);
            }
        } else {
            $number = null;
        }

        $this->getParentLocation($client->getLocation()->getLocation());

        return new Response($this->renderView('pages/professionalProfile.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
            'domain' => $this->locale->getDomain(),
            'clients' => $this->clientRepository->findByLocation([], ['id' => 'DESC'], 12, 50),
            'number' => $number ?? null,
            'activities' => $recursion->run($client->getActivity()),
            'servedZones' => $recursion->run($client->getServedZone())
        ]));
    }

    /**
     * @param ServedZone $location
     */
    public function getParentLocation(ServedZone $location): void
    {
        if ($location->getType() === ServedZone::COUNTRY) {
            $this->clientCountry = $location;
        } else {
            $this->getParentLocation($location->getParent());
        }
    }
}
