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
     *     "default" : "/newSubscribers",
     *      "fr" : "/newSubscribers-fr",
     *      "en" : "/newSubscribers-en",
     *      "es" : "/newSubscribers-es",
     *      "de" : "/newSubscribers-de",
     *      "it" : "/newSubscribers-it",
     *      "pt" : "/newSubscribers-pt",
     *      "be" : "/newSubscribers-be",
     *      "ad" : "/newSubscribers-ad",
     *      "ro" : "/newSubscribers-ro",
     *      "ma" : "/newSubscribers-ma",
     *      "ci" : "/newSubscribers-ci"
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