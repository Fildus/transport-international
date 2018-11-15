<?php

namespace App\Controller;


use App\Services\Locale;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @var Locale
     */
    private $locale;

    public function __construct(Locale $locale)
    {
        $locale->setLocale();
        $this->locale = $locale;
    }

    /**
     * @Route({
     *      "default" : "/login",
     *      "fr" : "/login-fr",
     *      "en" : "/login-en",
     *      "es" : "/login-es",
     *      "de" : "/login-de",
     *      "it" : "/login-it",
     *      "pt" : "/login-pt",
     *      "be" : "/login-be",
     *      "ad" : "/login-ad",
     *      "ro" : "/login-ro",
     *      "ma" : "/login-ma",
     *      "ci" : "/login-ci"
     * }, name="_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('home');
    }
}