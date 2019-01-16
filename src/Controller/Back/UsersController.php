<?php

namespace App\Controller\Back;

use App\Entity\Client;
use App\Entity\Search\UserSearch;
use App\Entity\User;
use App\Form\Back\User\UserType;
use App\Form\Search\UserSearchType;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller\backOffice
 * @Route("/admin/utilisateurs", name="_admin_users")
 */
class UsersController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(UserRepository $userRepository, ObjectManager $manager)
    {
        $this->userRepository = $userRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/index/{page}", name="_index", defaults={"page":1})
     * @param                    $page
     * @param PaginatorInterface $paginator
     * @param Request            $request
     *
     * @return Response
     */
    public function index($page, PaginatorInterface $paginator, Request $request)
    {
        $search = new UserSearch();
        $searchForm = $this->createForm(UserSearchType::class, $search);
        $searchForm->handleRequest($request);

        $users = $paginator->paginate(
            $this->userRepository->getAllUsers($searchForm->getViewData()),
            $page
        );

        return $this->render('backOffice/pages/user/index.html.twig', [
            'users' => $users,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/edition/{id}", name="_edit")
     * @param         $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit($id, Request $request)
    {
        $user = $this->userRepository->find($id);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($user);
            $this->manager->flush();

            return $this->redirectToRoute('_admin_activities_edit', ['id' => $user->getId()]);
        }

        return $this->render('backOffice/pages/user/types/userEdit.html.twig', [
            'u' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/nouveau", name="_new")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $user = new User();
        $user->setRole(User::USER);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($user);
            $this->manager->flush();

            return $this->redirectToRoute('_admin_users_edit', ['id' => $user->getId()]);
        }

        return $this->render('backOffice/pages/user/types/userNew.html.twig', [
            'u' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete")
     * @param                  $id
     * @param ClientRepository $clientRepository
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id, ClientRepository $clientRepository)
    {
        /** @var $activity User */
        $user = $this->userRepository->find($id);
        /** @var $client Client */
        $client = $clientRepository->findOneByUser($user);
        if ($client !== null) {
            $this->manager->remove($client);
        }
        $this->manager->remove($user);
        $this->manager->flush();

        $this->addFlash('success', 'l\'utilisateur <strong>' . $user->getUsername() . ' </strong>a été supprimée');
        return $this->redirectToRoute('_admin_users_index');
    }
}
