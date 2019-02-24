<?php

namespace App\Controller\Back;


use App\Entity\About;
use App\Entity\Client;
use App\Entity\Contact;
use App\Entity\CoreBusiness;
use App\Entity\Equipment;
use App\Entity\LegalInformation;
use App\Entity\Location;
use App\Entity\Managers;
use App\Entity\Search\ClientSearch;
use App\Entity\User;
use App\Form\Back\PasswordClientType;
use App\Form\Back\ClientValidatedType;
use App\Form\Front\AboutType;
use App\Form\Front\ActivityType;
use App\Form\Front\ContactType;
use App\Form\Front\CoreBusinessType;
use App\Form\Front\EquipmentType;
use App\Form\Front\LegalInformationType;
use App\Form\Front\LocationType;
use App\Form\Front\ManagersType;
use App\Form\Front\ServedZoneType;
use App\Form\Back\UserType;
use App\Form\Search\ClientSearchType;
use App\Repository\ActivityRepository;
use App\Repository\ClientRepository;
use App\Repository\ServedZoneRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 * @package App\Controller\backOffice
 * @Route("/admin", name="_admin_client")
 */
class ClientController extends AbstractController
{

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var ClientRepository
     */
    private $clientRepository;
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(ObjectManager $manager, ClientRepository $clientRepository, CacheInterface $cache)
    {
        $this->manager = $manager;
        $this->clientRepository = $clientRepository;
        $this->cache = $cache;
    }

    /**
     * @Route("/clients/{page}", name="_index", methods="GET", defaults={"page":1})
     * @param ClientRepository   $clientRepository
     * @param PaginatorInterface $paginator
     * @param int                $page
     * @param Request            $request
     *
     * @return Response
     */
    public function index(ClientRepository $clientRepository, PaginatorInterface $paginator, $page, Request $request): Response
    {
        $search = new ClientSearch();
        $searchForm = $this->createForm(ClientSearchType::class, $search);
        $searchForm->handleRequest($request);

        $clients = $paginator->paginate(
            $clientRepository->getAllClients($searchForm->getViewData()),
            $page
        );

        return $this->render('backOffice/pages/client/index.html.twig', [
            'searchForm' => $searchForm->createView(),
            'clients' => $clients
        ]);
    }

    /**
     * @Route("/client/{clientId}/informations-legales", name="_edit_legalInformation")
     * @param ClientRepository $client
     * @param                  $clientId
     * @param Request          $request
     *
     * @return Response
     */
    public function legalInformationEdit(ClientRepository $client, $clientId, Request $request): Response
    {
        $c = $client->find($clientId);

        $legalInformation = $c->getLegalInformation() ?? new LegalInformation();

        $form = $this->createForm(LegalInformationType::class, $legalInformation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $c->setLegalInformation($legalInformation);
            $this->manager->persist($c);
            $this->manager->flush();
            $this->addFlash('success', 'Les modifications ont bien été pris en compte');
        }

        return $this->render('backOffice/pages/client/types/legalInformation.html.twig', [
            'form' => $form->createView(),
            'c' => $c
        ]);
    }

    /**
     * @Route("/client/{clientId}/localisation", name="_edit_location")
     * @param ClientRepository $client
     * @param                  $clientId
     * @param Request          $request
     *
     * @return Response
     */
    public function localisationEdit(ClientRepository $client, $clientId, Request $request): Response
    {
        $c = $client->find($clientId);

        $location = $c->getLocation() ?? new Location();

        $form = $this->createForm(LocationType::class, $location);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $c->setLocation($location);
            $this->manager->persist($c);
            $this->manager->flush();
            $this->addFlash('success', 'Les modifications ont bien été pris en compte');
        }

        return $this->render('backOffice/pages/client/types/location.html.twig', [
            'form' => $form->createView(),
            'c' => $c
        ]);
    }

    /**
     * @Route("/client/{clientId}/contact", name="_edit_contact")
     * @param ClientRepository $client
     * @param                  $clientId
     * @param Request          $request
     *
     * @return Response
     */
    public function contactEdit(ClientRepository $client, $clientId, Request $request): Response
    {
        $c = $client->find($clientId);

        $contact = $c->getContact() ?? new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $c->setContact($contact);
            $this->manager->persist($c);
            $this->manager->flush();
            $this->addFlash('success', 'Les modifications ont bien été pris en compte');
        }

        return $this->render('backOffice/pages/client/types/contact.html.twig', [
            'form' => $form->createView(),
            'c' => $c
        ]);
    }

    /**
     * @Route("/client/{clientId}/activite-principale", name="_edit_coreBusiness")
     * @param ClientRepository $client
     * @param                  $clientId
     * @param Request          $request
     *
     * @return Response
     */
    public function coreBusinessEdit(ClientRepository $client, $clientId, Request $request): Response
    {
        $c = $client->find($clientId);

        $coreBusiness = $c->getCoreBusiness() ?? new CoreBusiness();

        $form = $this->createForm(CoreBusinessType::class, $coreBusiness);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $c->setCoreBusiness($coreBusiness);
            $this->manager->persist($c);
            $this->manager->flush();
            $this->addFlash('success', 'Les modifications ont bien été pris en compte');
        }

        return $this->render('backOffice/pages/client/types/coreBusiness.html.twig', [
            'form' => $form->createView(),
            'c' => $c
        ]);
    }

    /**
     * @Route("/client/{clientId}/utilisateur", name="_edit_user")
     * @param ClientRepository $client
     * @param                  $clientId
     * @param Request          $request
     *
     * @return Response
     */
    public function userEdit(ClientRepository $client, $clientId, Request $request): Response
    {
        $c = $client->find($clientId);

        $user = $c->getUser() ?? new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $c->setUser($user);
            $this->manager->persist($c);
            $this->manager->flush();
            $this->addFlash('success', 'Les modifications ont bien été pris en compte');
        }

        return $this->render('backOffice/pages/client/types/user.html.twig', [
            'form' => $form->createView(),
            'c' => $c
        ]);
    }

    /**
     * @Route("/client/{clientId}/password", name="_edit_password")
     * @param ClientRepository $client
     * @param                  $clientId
     * @param Request          $request
     *
     * @return Response
     */
    public function passwordEdit(ClientRepository $client, $clientId, Request $request): Response
    {
        $c = $client->find($clientId);

        $user = $c->getUser() ?? new User();

        $form = $this->createForm(PasswordClientType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $c->setUser($user);
            $this->manager->persist($c);
            $this->manager->flush();
            $this->addFlash('success', 'Les modifications ont bien été pris en compte');
        }

        return $this->render('backOffice/pages/client/types/password.html.twig', [
            'form' => $form->createView(),
            'c' => $c
        ]);
    }

    /**
     * @Route("/client/{clientId}/managers", name="_edit_managers")
     * @param ClientRepository $client
     * @param                  $clientId
     * @param Request          $request
     *
     * @return Response
     */
    public function managersEdit(ClientRepository $client, $clientId, Request $request): Response
    {
        $c = $client->find($clientId);

        $managers = $c->getManagers() ?? new Managers();

        $form = $this->createForm(ManagersType::class, $managers);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $c->setManagers($managers);
            $this->manager->persist($c);
            $this->manager->flush();
            $this->addFlash('success', 'Les modifications ont bien été pris en compte');
        }

        return $this->render('backOffice/pages/client/types/managers.html.twig', [
            'form' => $form->createView(),
            'c' => $c
        ]);
    }


    /**
     * @Route("/client/{clientId}/equipements", name="_edit_equipment")
     * @param ClientRepository $client
     * @param                  $clientId
     * @param Request          $request
     *
     * @return Response
     */
    public function equipmentEdit(ClientRepository $client, $clientId, Request $request): Response
    {
        $c = $client->find($clientId);

        $equipment = $c->getEquipment() ?? new Equipment();

        $form = $this->createForm(EquipmentType::class, $equipment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $c->setEquipment($equipment);
            $this->manager->persist($c);
            $this->manager->flush();
            $this->addFlash('success', 'Les modifications ont bien été pris en compte');
        }

        return $this->render('backOffice/pages/client/types/equipment.html.twig', [
            'form' => $form->createView(),
            'c' => $c
        ]);
    }

    /**
     * @Route("/client/{clientId}/a-propos", name="_edit_about")
     * @param ClientRepository $client
     * @param                  $clientId
     * @param Request          $request
     *
     * @return Response
     */
    public function aboutEdit(ClientRepository $client, $clientId, Request $request): Response
    {
        $c = $client->find($clientId);

        $about = $c->getAbout() ?? new About();

        $form = $this->createForm(AboutType::class, $about);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $c->setAbout($about);
            $this->manager->persist($c);
            $this->manager->flush();
            $this->addFlash('success', 'Les modifications ont bien été pris en compte');
        }

        return $this->render('backOffice/pages/client/types/about.html.twig', [
            'form' => $form->createView(),
            'c' => $c
        ]);
    }

    /**
     * @Route("/client/{clientId}/activites", name="_edit_activity")
     * @param ClientRepository   $client
     * @param ActivityRepository $activityRepository
     * @param                    $clientId
     * @param Request            $request
     *
     * @return Response
     */
    public function activityEdit(ClientRepository $client, ActivityRepository $activityRepository, $clientId, Request $request): Response
    {
        $request->setLocale('fr');

        $c = $client->find($clientId);

        $form = $this->createForm(ActivityType::class, $c);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($c);
            $this->manager->flush();
            $this->addFlash('success', 'Les modifications ont bien été pris en compte');
        }

        return $this->render('backOffice/pages/client/types/activity.html.twig', [
            'form' => $form->createView(),
            'c' => $c,
            'activities' => $activityRepository->findByWithTranslation()//$this->cache->get($key)
        ]);
    }

    /**
     * @Route("/client/{clientId}/zones-desservies", name="_edit_servedZone")
     * @param ClientRepository     $client
     * @param ServedZoneRepository $servedZoneRepository
     * @param                      $clientId
     * @param Request              $request
     *
     * @return Response
     */
    public function servedZoneEdit(ClientRepository $client, ServedZoneRepository $servedZoneRepository, $clientId, Request $request): Response
    {
        $request->setLocale('fr');

        $c = $client->find($clientId);

        $form = $this->createForm(ServedZoneType::class, $c);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($c);
            $this->manager->flush();
            $this->addFlash('success', 'Les modifications ont bien été pris en compte');
        }

        return $this->render('backOffice/pages/client/types/servedZone.html.twig', [
            'form' => $form->createView(),
            'c' => $c,
            'servedZone' => $servedZoneRepository->findByWithTranslation()
        ]);
    }

    /**
     * @Route("/client/nouveau-client", name="_new")
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request)
    {
        $u = new User();
        $u->setRole('ROLE_USER');
        $u->setPassword(random_int(100000, 1000000));

        $form = $this->createForm(UserType::class, $u);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $c = new Client();
            $c->setUser($u);
            $this->manager->persist($c);
            $this->manager->flush();

            $this->addFlash('success', 'Le client dont le mail est <strong>' . $c->getUser()->getUsername() . '</strong>, a été créé');
            return $this->redirectToRoute('_admin_client_edit_legalInformation', ['clientId' => $c->getId()]);
        }

        return $this->render('backOffice/pages/client/types/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/client/{id}", name="_delete")
     * @param $id
     *
     * @return Response
     * @throws \Exception
     */
    public function delete($id)
    {
        $c = $this->clientRepository->find($id);

        $this->addFlash('warning', 'Le client <strong>' . $c->getLegalInformation()->getCorporateName() . '</strong> a été supprimé');

        $this->manager->remove($c);
        $this->manager->flush();

        return $this->redirectToRoute('_admin_client_index', ['page' => 1]);
    }

    /**
     * @Route("/client/{clientId}/validated", name="_edit_validated")
     * @param ClientRepository $client
     * @param                  $clientId
     * @param Request          $request
     *
     * @return Response
     */
    public function validatedEdit(ClientRepository $client, $clientId, Request $request): Response
    {
        $c = $client->find($clientId);

        $form = $this->createForm(ClientValidatedType::class, $c);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($c);
            $this->manager->flush();
            $this->addFlash('success', 'Les modifications ont bien été pris en compte');
        }

        return $this->render('backOffice/pages/client/types/about.html.twig', [
            'form' => $form->createView(),
            'c' => $c
        ]);
    }
}