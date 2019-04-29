<?php

namespace App\Controller\Front;


use App\Services\Locale;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request             $request
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        if ($request->getSession() && $request->getSession()->has('new_client')) {
            $user = $request->getSession()->get('new_client');
        }

        return $this->getUser() ?
            $this->redirectToRoute('home') :
            $this->render('security/login.html.twig', [
                'last_username' => isset($user) && $user !== null ? $user->getUser()->getUserName() : $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError()
            ]);
    }

    /**
     * @Route("/login-success", name="_loginSuccess")
     */
    public function onSuccess(): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRole() === 'ROLE_USER') {
                return $this->redirectToRoute('account_legalInformation.' . $this->locale);
            }
            if ($this->getUser()->getRole() === 'ROLE_ADMIN') {
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