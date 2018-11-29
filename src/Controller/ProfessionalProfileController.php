<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfessionalProfileController extends AbstractController
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * @Route({
     *      "default": "/professional/{cnSlug}",
     *      "fr" : "/professional-fr/{cnSlug}",
     *      "en" : "/professional-en/{cnSlug}",
     *      "es" : "/professional-es/{cnSlug}",
     *      "de" : "/professional-de/{cnSlug}",
     *      "it" : "/professional-it/{cnSlug}",
     *      "pt" : "/professional-pt/{cnSlug}",
     *      "be" : "/professional-be/{cnSlug}",
     *      "ad" : "/professional-ad/{cnSlug}",
     *      "ro" : "/professional-ro/{cnSlug}",
     *      "ma" : "/professional-ma/{cnSlug}",
     *      "ci" : "/professional-ci/{cnSlug}",
     * }, name="_professional_profile")
     * @param $cnSlug
     * @return Response
     */
    public function profile($cnSlug): Response
    {
        $client = $this->clientRepository->getClientProfile($cnSlug);
        dump($client);
        return new Response($this->renderView('professionalProfile.html.twig', [
            'client' => $client
        ]));
    }
}
