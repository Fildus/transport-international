<?php

namespace App\Controller\Back;


use App\Repository\ClientRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 * @package App\Controller\backOffice
 * @Route("/admin", name="_admin")
 */
class HomeController extends AbstractController
{
    /**
     * @Route(name="_home")
     * @param ClientRepository $clientRepository
     * @return Response
     */
    public function home(ClientRepository $clientRepository)
    {
        //@method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)

//        $lastClients = $clientRepository->findLasts(10);
        return new Response($this->renderView('backOffice/pages/home.html.twig'));
    }

}