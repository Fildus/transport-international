<?php

namespace App\Controller\Front;


use App\Entity\Client;
use App\Services\Locale;
use App\Form\Front\RegisterClientType;
use App\Services\Mailer;
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
     *      "default": "/registration",
     *      "fr" : "/inscription",
     *      "en" : "/registration",
     *      "es" : "/registro",
     *      "de" : "/anmeldung",
     *      "it" : "/iscrizione",
     *      "pt" : "/inscricao",
     *      "be" : "/inscription",
     *      "ad" : "/inscription",
     *      "ro" : "/inregistrare",
     *      "ma" : "/inscription",
     *      "ci" : "/inscription",
     * }, name="_register")
     * @param Request $request
     * @param ObjectManager $manager
     *
     * @param Mailer $mailer
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, Mailer $mailer): Response
    {
        if ($this->getUser() !== null) {
            return $this->redirectToRoute('home');
        }
        /**
         * @var $client Client
         */
        $client = new Client();
        $form = $this->createForm(RegisterClientType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($client);
            $manager->flush();
            $this->addFlash('success', 'Votre compte a bien été créé');
            $mailer->send(getenv('EMAIL'), $this->renderView('mail/mail.html.twig', [
                'content' => [
                    'url' => 'https://www.transport-international.com' . $this->generateUrl('_admin_client_edit_legalInformation', ['clientId' => $client->getId()]),
                    'client' => $client
                ]
            ]));
            $request->getSession()->set('new_client', $client);
            return $this->redirectToRoute('_login');
        }

        return new Response($this->renderView('client/register.html.twig', [
            'domains' => $this->locale->getAllMainDomain(),
            'form' => $form->createView(),
            'domain' => $this->locale->getDomain()
        ]));
    }
}