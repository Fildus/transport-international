<?php

namespace App\Controller\Back;


use App\Entity\Client;
use App\Entity\Search\ClientSearch;
use App\Form\Search\ClientSearchType;
use App\Repository\ClientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 * @package App\Controller\backOffice
 * @Route("/admin", name="_client")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/clients/{page}", name="_index", methods="GET", defaults={"page":1})
     * @param ClientRepository $clientRepository
     * @param PaginatorInterface $paginator
     * @param int $page
     * @param Request $request
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
            'clients' => $clients//$clientRepository->findBy([], ['id' => 'ASC'], 10)
        ]);
    }

//    /**
//     * @Route("/new", name="_new", methods="GET|POST")
//     */
//    public function new(Request $request): Response
//    {
//        $user = new User();
//        $form = $this->createForm(UserType::class, $user);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($user);
//            $em->flush();
//
//            return $this->redirectToRoute('user_index');
//        }
//
//        return $this->render('user/new.html.twig', [
//            'user' => $user,
//            'form' => $form->createView(),
//        ]);
//    }

    /**
     * @Route("/client/{id}", name="_show", methods="GET")
     * @param Client $client
     * @return Response
     */
    public function show(Client $client): Response
    {
        return $this->render('backOffice/pages/client/show.html.twig', ['c' => $client]);
    }

//    /**
//     * @Route("/{id}/edit", name="_edit", methods="GET|POST")
//     */
//    public function edit(Request $request, User $user): Response
//    {
//        $form = $this->createForm(UserType::class, $user);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('user_index', ['id' => $user->getId()]);
//        }
//
//        return $this->render('user/edit.html.twig', [
//            'user' => $user,
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * @Route("/{id}", name="_delete", methods="DELETE")
//     */
//    public function delete(Request $request, User $user): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($user);
//            $em->flush();
//        }
//
//        return $this->redirectToRoute('user_index');
//    }

}