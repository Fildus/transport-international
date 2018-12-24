<?php

namespace App\Controller\Back;


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
     * @return Response
     */
    public function home()
    {
        return $this->render('backOffice/pages/home/home.html.twig');
    }
}