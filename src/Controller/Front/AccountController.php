<?php

namespace App\Controller\Front;

use App\Entity\About;
use App\Entity\Client;
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
 *      "fr" : "/compte",
 *      "en" : "/account",
 *      "es" : "/cuenta",
 *      "de" : "/Konto",
 *      "it" : "/account",
 *      "pt" : "/conta",
 *      "be" : "/compte",
 *      "ad" : "/compte",
 *      "ro" : "/cont",
 *      "ma" : "/compte",
 *      "ci" : "/compte"
 * }, name="account")
 */
class AccountController extends AbstractController
{
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
     *
     * @param Locale           $locale
     * @param ClientRepository $clientRepository
     * @param ObjectManager    $objectManager
     * @param CacheInterface   $cache
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(Locale $locale, ClientRepository $clientRepository, ObjectManager $objectManager, CacheInterface $cache)
    {
        $locale->setLocale();
        $this->clientRepository = $clientRepository;
        $this->objectManager = $objectManager;
        $this->cache = $cache;
    }

    /**
     * @Route({
     *     "default" : "/legalInformation",
     *      "fr" : "/informations-legales",
     *      "en" : "/legal-information",
     *      "es" : "/informacion-legal",
     *      "de" : "/rechtliche-informationen",
     *      "it" : "/informazioni-legali",
     *      "pt" : "/informacao-legal",
     *      "be" : "/informations-legales",
     *      "ad" : "/informations-legales",
     *      "ro" : "/informatii-juridice",
     *      "ma" : "/informations-legales",
     *      "ci" : "/informations-legales"
     * }, name="_legalInformation")
     * @param Request $request
     *
     * @return Response
     */
    public function legalInformation(Request $request): Response
    {
        if ($this->getUser() !== null && $this->getUser()->getRole() === 'ROLE_ADMIN') {
            return $this->redirectToRoute('_admin_home');
        }

        $client = $this->getClient();
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
     *      "fr" : "/localisation",
     *      "en" : "/location",
     *      "es" : "/ubicacion",
     *      "de" : "/lage",
     *      "it" : "/posizione",
     *      "pt" : "/localizacao",
     *      "be" : "/localisation",
     *      "ad" : "/localisation",
     *      "ro" : "/locatie",
     *      "ma" : "/localisation",
     *      "ci" : "/localisation"
     * }, name="_location")
     * @param Request $request
     *
     * @return Response
     */
    public function location(Request $request): Response
    {
        $client = $this->getClient();
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
     *      "fr" : "/contact",
     *      "en" : "/contact",
     *      "es" : "/contactar",
     *      "de" : "/Kontakt",
     *      "it" : "/contatto",
     *      "pt" : "/contato",
     *      "be" : "/contact",
     *      "ad" : "/contact",
     *      "ro" : "/contact",
     *      "ma" : "/contact",
     *      "ci" : "/contact"
     * }, name="_contact")
     * @param Request $request
     *
     * @return Response
     */
    public function contact(Request $request): Response
    {
        $client = $this->getClient();
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
     *      "fr" : "/coeur-de-metier",
     *      "en" : "/core-business",
     *      "es" : "/negocio-central",
     *      "de" : "/kerngeschaft",
     *      "it" : "/attivita-principale",
     *      "pt" : "/negocio-principal",
     *      "be" : "/coeur-de-metier",
     *      "ad" : "/coeur-de-metier",
     *      "ro" : "/afacere-principala",
     *      "ma" : "/coeur-de-metier",
     *      "ci" : "/coeur-de-metier"
     * }, name="_coreBusiness")
     * @param Request $request
     *
     * @return Response
     */
    public function coreBusiness(Request $request): Response
    {
        $client = $this->getClient();
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
     *      "fr" : "/utilisateur",
     *      "en" : "/user",
     *      "es" : "/usuario",
     *      "de" : "/Nutzer",
     *      "it" : "/utente",
     *      "pt" : "/utilizador",
     *      "be" : "/utilisateur",
     *      "ad" : "/utilisateur",
     *      "ro" : "/utilizator",
     *      "ma" : "/utilisateur",
     *      "ci" : "/utilisateur"
     * }, name="_user")
     * @param Request $request
     *
     * @return Response
     */
    public function user(Request $request): Response
    {
        $client = $this->getClient();
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
     *      "fr" : "/gestionnaires",
     *      "en" : "/managers",
     *      "es" : "/gerentes",
     *      "de" : "/managers",
     *      "it" : "/managers",
     *      "pt" : "/gerentes",
     *      "be" : "/gestionnaires",
     *      "ad" : "/gestionnaires",
     *      "ro" : "/manageri",
     *      "ma" : "/gestionnaires",
     *      "ci" : "/gestionnaires"
     * }, name="_managers")
     * @param Request $request
     *
     * @return Response
     */
    public function managers(Request $request): Response
    {
        $client = $this->getClient();
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
     *      "fr" : "/equipement",
     *      "en" : "/equipment",
     *      "es" : "/equipo",
     *      "de" : "/ausrustung",
     *      "it" : "/attrezzatura",
     *      "pt" : "/equipamento",
     *      "be" : "/equipement",
     *      "ad" : "/equipement",
     *      "ro" : "/echipament",
     *      "ma" : "/equipement",
     *      "ci" : "/equipement"
     * }, name="_equipment")
     * @param Request $request
     *
     * @return Response
     */
    public function equipment(Request $request): Response
    {
        $client = $this->getClient();
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
     *      "fr" : "/a-propos",
     *      "en" : "/about",
     *      "es" : "/a-proposito",
     *      "de" : "/uber",
     *      "it" : "/su",
     *      "pt" : "/sobre",
     *      "be" : "/a-propos",
     *      "ad" : "/a-propos",
     *      "ro" : "/despre",
     *      "ma" : "/a-propos",
     *      "ci" : "/a-propos"
     * }, name="_about")
     * @param Request $request
     *
     * @return Response
     */
    public function about(Request $request): Response
    {
        $client = $this->getClient();
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
     *      "fr" : "/activite",
     *      "en" : "/activity",
     *      "es" : "/actividad",
     *      "de" : "/aktivitat",
     *      "it" : "/attivita",
     *      "pt" : "/atividade",
     *      "be" : "/activite",
     *      "ad" : "/activite",
     *      "ro" : "/activitate",
     *      "ma" : "/activite",
     *      "ci" : "/activite"
     * }, name="_activity")
     * @param Request            $request
     * @param ActivityRepository $activityRepository
     *
     * @return Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function activity(Request $request, ActivityRepository $activityRepository): Response
    {
        $client = $this->getClient();
        $form = $this->createForm(ActivityType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->objectManager->persist($client);
            $this->objectManager->flush();
        }

        $key = 'activity';
        if (!$this->cache->has($key)) {
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
     *      "fr" : "/localisations-desservies",
     *      "en" : "/locations-served",
     *      "es" : "/lugares-servidos",
     *      "de" : "/standorte-serviert",
     *      "it" : "/posizioni-servite",
     *      "pt" : "/locais-servidos",
     *      "be" : "/localisations-desservies",
     *      "ad" : "/localisations-desservies",
     *      "ro" : "/locurile-deservite",
     *      "ma" : "/localisations-desservies",
     *      "ci" : "/localisations-desservies"
     * }, name="_servedZone")
     * @param Request              $request
     * @param ServedZoneRepository $servedZoneRepository
     *
     * @return Response
     */
    public function servedZone(Request $request, ServedZoneRepository $servedZoneRepository): Response
    {
        $client = $this->getClient();
        $form = $this->createForm(ServedZoneType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->objectManager->persist($client);
            $this->objectManager->flush();
        }

        return new Response($this->renderView('client/account/pages/servedZone.html.twig', [
            'form' => $form->createView(),
            'servedZone' => $servedZoneRepository->findByWithTranslation()
        ]));
    }

    /**
     * @return \App\Entity\Client
     */
    private function getClient(): Client
    {
        return $this->clientRepository->findOneBy([
                'user' => $this->getUser()
            ]) ?? new Client();
    }
}