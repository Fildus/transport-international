<?php

namespace App\Controller\Front;


use App\Repository\ClientRepository;
use App\Services\Locale;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NewSubscribersController extends AbstractController
{

    /**
     * @var Locale
     */
    private $locale;

    /**
     * NewSubscribersController constructor.
     *
     * @param Locale $locale
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(Locale $locale)
    {
        $this->locale = $locale;
        $locale->setLocale();
    }

    /**
     * @Route({
     *     "default" : "/new-subscribers",
     *      "fr" : "/nouveaux-inscrits",
     *      "en" : "/new-subscribers",
     *      "es" : "/nuevos-suscriptores",
     *      "de" : "/neue-abonnenten",
     *      "it" : "/nuovi-abbonati",
     *      "pt" : "/novos-assinantes",
     *      "be" : "/nouveaux-inscrits",
     *      "ad" : "/nouveaux-inscrits",
     *      "ro" : "/noi-abonati",
     *      "ma" : "/nouveaux-inscrits",
     *      "ci" : "/nouveaux-inscrits"
     * }, name="_newSubscribers")
     * @param ClientRepository $clientRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function newSubscribers(ClientRepository $clientRepository)
    {
        return $this->render('pages/newSubscribers.html.twig', [
            'clients' => $clientRepository->lastClients(60),
            'domain' => $this->locale->getDomain()
        ]);
    }
}