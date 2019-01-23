<?php

namespace App\Controller\Front;


use App\Services\Locale;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /** @var Locale */
    private $locale;

    /** @var ContainerInterface */
    protected $container;

    /**
     * Controller constructor.
     *
     * @param Locale             $locale
     * @param ContainerInterface $container
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(Locale $locale, ContainerInterface $container)
    {
        $locale->setLocale();
        $this->locale = $locale->getLocalematched();
        $this->container = $container;
    }

    /**
     * @Route({
     *      "default" : "/login",
     *      "fr" : "/login",
     *      "en" : "/login",
     *      "es" : "/login",
     *      "de" : "/login",
     *      "it" : "/login",
     *      "pt" : "/login",
     *      "be" : "/login",
     *      "ad" : "/login",
     *      "ro" : "/login",
     *      "ma" : "/login",
     *      "ci" : "/login"
     * }, name="_login")
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->getUser() ?
            $this->redirectToRoute('home') :
            $this->render('security/login.html.twig', [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError()
            ]);
    }

    /**
     * @Route("/login-success", name="_loginSuccess")
     */
    public function onSuccess(): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRole() === 'ROLE_USER'){
                return $this->redirectToRoute('account_legalInformation.' . $this->locale);
            }
            if ($this->getUser()->getRole() === 'ROLE_ADMIN'){
                return $this->redirectToRoute('_admin_home');
            }
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('home');
    }
}