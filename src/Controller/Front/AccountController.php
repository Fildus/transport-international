<?php

namespace App\Controller\Front;

use App\Entity\About;
use App\Entity\Contact;
use App\Entity\CoreBusiness;
use App\Entity\Equipment;
use App\Entity\LegalInformation;
use App\Entity\Location;
use App\Entity\Managers;
use App\Entity\User;
use App\Form\Front\AboutType;
use App\Form\Front\ActivityType;
use App\Form\Front\ContactType;
use App\Form\Front\CoreBusinessType;
use App\Form\Front\EquipmentType;
use App\Form\Front\LegalInformationType;
use App\Form\Front\LocationType;
use App\Form\Front\ManagersType;
use App\Form\Front\ServedZoneType;
use App\Form\Front\UserType;
use App\Repository\ActivityRepository;
use App\Repository\ClientRepository;
use App\Repository\ServedZoneRepository;
use App\Services\Locale;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UserController
 * @package App\Controller
 * @Route({
 *     "default" : "/account",
 *      "fr" : "/account-fr",
 *      "en" : "/account-en",
 *      "es" : "/account-es",
 *      "de" : "/account-de",
 *      "it" : "/account-it",
 *      "pt" : "/account-pt",
 *      "be" : "/account-be",
 *      "ad" : "/account-ad",
 *      "ro" : "/account-ro",
 *      "ma" : "/account-ma",
 *      "ci" : "/account-ci"
 * }, name="account")
 */
class AccountController extends AbstractController
{
    /**
     * @var Locale
     */
    private $locale;

    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * AccountController constructor.
     * @param Locale $locale
     * @param ClientRepository $clientRepository
     * @param ObjectManager $objectManager
     * @param CacheInterface $cache
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(Locale $locale, ClientRepository $clientRepository, ObjectManager $objectManager, CacheInterface $cache)
    {
        $locale->setLocale();
        $this->locale = $locale;
        $this->clientRepository = $clientRepository;
        $this->objectManager = $objectManager;
        $this->cache = $cache;
    }

    /**
     * @Route({
     *     "default" : "/legalInformation",
     *      "fr" : "/legalInformation-fr",
     *      "en" : "/legalInformation-en",
     *      "es" : "/legalInformation-es",
     *      "de" : "/legalInformation-de",
     *      "it" : "/legalInformation-it",
     *      "pt" : "/legalInformation-pt",
     *      "be" : "/legalInformation-be",
     *      "ad" : "/legalInformation-ad",
     *      "ro" : "/legalInformation-ro",
     *      "ma" : "/legalInformation-ma",
     *      "ci" : "/legalInformation-ci"
     * }, name="_legalInformation")
     * @param Request $request
     * @return Response
     */
    public function legalInformation(Request $request)
    {
        $client = $this->clientRepository->findOneBy([
            'user' => $this->getUser()
        ]);
        $legalInformation = $client->getLegalInformation() ?? new LegalInformation();

        $form = $this->createForm(LegalInformationType::class, $legalInformation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setLegalInformation($legalInformation);
            $this->objectManager->persist($client);
            $this->objectManager->flush();
        }

        return new Response($this->renderView('client/account/pages/legalInformation.html.twig', [
            'form' => $form->createView()
        ]));
    }

    /**
     * @Route({
     *     "default" : "/location",
     *      "fr" : "/location-fr",
     *      "en" : "/location-en",
     *      "es" : "/location-es",
     *      "de" : "/location-de",
     *      "it" : "/location-it",
     *      "pt" : "/location-pt",
     *      "be" : "/location-be",
     *      "ad" : "/location-ad",
     *      "ro" : "/location-ro",
     *      "ma" : "/location-ma",
     *      "ci" : "/location-ci"
     * }, name="_location")
     * @param Request $request
     * @return Response
     */
    public function location(Request $request)
    {
        $client = $this->clientRepository->findOneBy([
            'user' => $this->getUser()
        ]);

        $location = $client->getLocation() ?? new Location();

        $form = $this->createForm(LocationType::class, $location);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setLocation($location);
            $this->objectManager->persist($client);
            $this->objectManager->flush();
        }

        return new Response($this->renderView('client/account/pages/location.html.twig', [
            'form' => $form->createView()
        ]));
    }

    /**
     * @Route({
     *     "default" : "/contact",
     *      "fr" : "/contact-fr",
     *      "en" : "/contact-en",
     *      "es" : "/contact-es",
     *      "de" : "/contact-de",
     *      "it" : "/contact-it",
     *      "pt" : "/contact-pt",
     *      "be" : "/contact-be",
     *      "ad" : "/contact-ad",
     *      "ro" : "/contact-ro",
     *      "ma" : "/contact-ma",
     *      "ci" : "/contact-ci"
     * }, name="_contact")
     * @param Request $request
     * @return Response
     */
    public function contact(Request $request)
    {
        $client = $this->clientRepository->findOneBy([
            'user' => $this->getUser()
        ]);
        $contact = $client->getContact() ?? new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setContact($contact);
            $this->objectManager->persist($client);
            $this->objectManager->flush();
        }

        return new Response($this->renderView('client/account/pages/contact.html.twig', [
            'form' => $form->createView()
        ]));
    }

    /**
     * @Route({
     *     "default" : "/coreBusiness",
     *      "fr" : "/coreBusiness-fr",
     *      "en" : "/coreBusiness-en",
     *      "es" : "/coreBusiness-es",
     *      "de" : "/coreBusiness-de",
     *      "it" : "/coreBusiness-it",
     *      "pt" : "/coreBusiness-pt",
     *      "be" : "/coreBusiness-be",
     *      "ad" : "/coreBusiness-ad",
     *      "ro" : "/coreBusiness-ro",
     *      "ma" : "/coreBusiness-ma",
     *      "ci" : "/coreBusiness-ci"
     * }, name="_coreBusiness")
     * @param Request $request
     * @return Response
     */
    public function coreBusiness(Request $request)
    {
        $client = $this->clientRepository->findOneBy([
            'user' => $this->getUser()
        ]);
        $coreBusiness = $client->getCoreBusiness() ?? new CoreBusiness();

        $form = $this->createForm(CoreBusinessType::class, $coreBusiness);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setCoreBusiness($coreBusiness);
            $this->objectManager->persist($client);
            $this->objectManager->flush();
        }

        return new Response($this->renderView('client/account/pages/coreBusiness.html.twig', [
            'form' => $form->createView()
        ]));
    }

    /**
     * @Route({
     *     "default" : "/user",
     *      "fr" : "/user-fr",
     *      "en" : "/user-en",
     *      "es" : "/user-es",
     *      "de" : "/user-de",
     *      "it" : "/user-it",
     *      "pt" : "/user-pt",
     *      "be" : "/user-be",
     *      "ad" : "/user-ad",
     *      "ro" : "/user-ro",
     *      "ma" : "/user-ma",
     *      "ci" : "/user-ci"
     * }, name="_user")
     * @param Request $request
     * @return Response
     */
    public function user(Request $request)
    {
        $client = $this->clientRepository->findOneBy([
            'user' => $this->getUser()
        ]);
        $user = $client->getUser() ?? new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setUser($user);
            $this->objectManager->persist($client);
            $this->objectManager->flush();
        }

        return new Response($this->renderView('client/account/pages/user.html.twig', [
            'form' => $form->createView()
        ]));
    }

    /**
     * @Route({
     *     "default" : "/managers",
     *      "fr" : "/managers-fr",
     *      "en" : "/managers-en",
     *      "es" : "/managers-es",
     *      "de" : "/managers-de",
     *      "it" : "/managers-it",
     *      "pt" : "/managers-pt",
     *      "be" : "/managers-be",
     *      "ad" : "/managers-ad",
     *      "ro" : "/managers-ro",
     *      "ma" : "/managers-ma",
     *      "ci" : "/managers-ci"
     * }, name="_managers")
     * @param Request $request
     * @return Response
     */
    public function managers(Request $request)
    {
        $client = $this->clientRepository->findOneBy([
            'user' => $this->getUser()
        ]);
        $managers = $client->getManagers() ?? new Managers();

        $form = $this->createForm(ManagersType::class, $managers);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setManagers($managers);
            $this->objectManager->persist($client);
            $this->objectManager->flush();
        }

        return new Response($this->renderView('client/account/pages/managers.html.twig', [
            'form' => $form->createView()
        ]));
    }

    /**
     * @Route({
     *     "default" : "/equipment",
     *      "fr" : "/equipment-fr",
     *      "en" : "/equipment-en",
     *      "es" : "/equipment-es",
     *      "de" : "/equipment-de",
     *      "it" : "/equipment-it",
     *      "pt" : "/equipment-pt",
     *      "be" : "/equipment-be",
     *      "ad" : "/equipment-ad",
     *      "ro" : "/equipment-ro",
     *      "ma" : "/equipment-ma",
     *      "ci" : "/equipment-ci"
     * }, name="_equipment")
     * @param Request $request
     * @return Response
     */
    public function equipment(Request $request)
    {
        $client = $this->clientRepository->findOneBy([
            'user' => $this->getUser()
        ]);
        $equipment = $client->getEquipment() ?? new Equipment();

        $form = $this->createForm(EquipmentType::class, $equipment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setEquipment($equipment);
            $this->objectManager->persist($client);
            $this->objectManager->flush();
        }

        return new Response($this->renderView('client/account/pages/managers.html.twig', [
            'form' => $form->createView()
        ]));
    }

    /**
     * @Route({
     *     "default" : "/about",
     *      "fr" : "/about-fr",
     *      "en" : "/about-en",
     *      "es" : "/about-es",
     *      "de" : "/about-de",
     *      "it" : "/about-it",
     *      "pt" : "/about-pt",
     *      "be" : "/about-be",
     *      "ad" : "/about-ad",
     *      "ro" : "/about-ro",
     *      "ma" : "/about-ma",
     *      "ci" : "/about-ci"
     * }, name="_about")
     * @param Request $request
     * @return Response
     */
    public function about(Request $request)
    {
        $client = $this->clientRepository->findOneBy([
            'user' => $this->getUser()
        ]);
        $about = $client->getAbout() ?? new About();

        $form = $this->createForm(AboutType::class, $about);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setAbout($about);
            $this->objectManager->persist($client);
            $this->objectManager->flush();
        }

        return new Response($this->renderView('client/account/pages/about.html.twig', [
            'form' => $form->createView()
        ]));
    }

    /**
     * @Route({
     *     "default" : "/activity",
     *      "fr" : "/activity-fr",
     *      "en" : "/activity-en",
     *      "es" : "/activity-es",
     *      "de" : "/activity-de",
     *      "it" : "/activity-it",
     *      "pt" : "/activity-pt",
     *      "be" : "/activity-be",
     *      "ad" : "/activity-ad",
     *      "ro" : "/activity-ro",
     *      "ma" : "/activity-ma",
     *      "ci" : "/activity-ci"
     * }, name="_activity")
     * @param Request $request
     * @param ActivityRepository $activityRepository
     * @return Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function activity(Request $request, ActivityRepository $activityRepository)
    {
        $client = $this->clientRepository->findOneBy([
            'user' => $this->getUser()
        ]);
        $form = $this->createForm(ActivityType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->objectManager->persist($client);
            $this->objectManager->flush();
        }

        $key = 'activity';
        if (!$this->cache->has($key)){
            $this->cache->set($key, $activityRepository->findByWithTranslation(), 3600);
        }

        return new Response($this->renderView('client/account/pages/activity.html.twig', [
            'form' => $form->createView(),
            'activities' => $activityRepository->findByWithTranslation()//$this->cache->get($key)
        ]));
    }

    /**
     * @Route({
     *     "default" : "/servedZone",
     *      "fr" : "/servedZone-fr",
     *      "en" : "/servedZone-en",
     *      "es" : "/servedZone-es",
     *      "de" : "/servedZone-de",
     *      "it" : "/servedZone-it",
     *      "pt" : "/servedZone-pt",
     *      "be" : "/servedZone-be",
     *      "ad" : "/servedZone-ad",
     *      "ro" : "/servedZone-ro",
     *      "ma" : "/servedZone-ma",
     *      "ci" : "/servedZone-ci"
     * }, name="_servedZone")
     * @param Request $request
     * @param ServedZoneRepository $servedZoneRepository
     * @return Response
     */
    public function servedZone(Request $request, ServedZoneRepository $servedZoneRepository)
    {
        $client = $this->clientRepository->findOneBy([
            'user' => $this->getUser()
        ]);
        $form = $this->createForm(ServedZoneType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->objectManager->persist($client);
            $this->objectManager->flush();
        }

//        $key = 'servedZone';
//        if (!$this->cache->has($key)){
//            $this->cache->set($key, $servedZoneRepository->findByWithTranslation(), 3600);
//        }

        return new Response($this->renderView('client/account/pages/servedZone.html.twig', [
            'form' => $form->createView(),
            'servedZone' => $servedZoneRepository->findByWithTranslation()//$this->cache->get($key)
        ]));
    }
}