<?php

namespace App\Controller;


use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController
{
    /**
     * @Route("/{req}", requirements={"req":".*\.html"})
     * @param string           $req
     * @param ClientRepository $clientRepository
     *
     * @return Response
     */
    public function redirectToProfessionalProfile(string $req, ClientRepository $clientRepository): Response
    {
        $test = [];
        preg_match('/\d+/', $req, $test);
        $client = $clientRepository->findOneBy([
            'id_oldDatabase' => (int)$test[0]
        ]);

        $client = $client !== null && $client->getLegalInformation() ? $client->getLegalInformation()->getSlug() : null;

        return $this->redirectToRoute('_professional_profile', [
            'cnSlug' => $client
        ], Response::HTTP_MOVED_PERMANENTLY);
    }
}