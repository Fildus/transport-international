<?php

namespace App\Controller\Front;


use App\Entity\Client;
use App\Services\Locale;
use App\Form\Front\RegisterClientType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    /**
     * @var Locale
     */
    private $locale;

    /**
     * RegisterController constructor.
     *
     * @param Locale $locale
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(Locale $locale)
    {
        $locale->setLocale();
        $this->locale = $locale;
    }

    /**
     * @Route({
     *      "default": "/register",
     *      "fr" : "/register-fr",
     *      "en" : "/register-en",
     *      "es" : "/register-es",
     *      "de" : "/register-de",
     *      "it" : "/register-it",
     *      "pt" : "/register-pt",
     *      "be" : "/register-be",
     *      "ad" : "/register-ad",
     *      "ro" : "/register-ro",
     *      "ma" : "/register-ma",
     *      "ci" : "/register-ci",
     * }, name="_register")
     * @param Request       $request
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager): Response
    {
        if ($this->getUser() !== null) {
            return $this->redirectToRoute('home');
        }
        $client = new Client();
        $form = $this->createForm(RegisterClientType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($client);
            $manager->flush();
            $this->addFlash('success', 'Votre compte a bien été créé');
            return $this->redirectToRoute('account_legalInformation');
        }

        return new Response($this->renderView('client/register.html.twig', [
            'domains' => $this->locale->getAllMainDomain(),
            'form' => $form->createView(),
            'domain' => $this->locale->getDomain()
        ]));
    }
}