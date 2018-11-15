<?php

namespace App\Controller;

use App\Services\Locale;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
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
     *     "fr" : "/",
     *     "en" : "/",
     *     "es" : "/",
     *     "ad" : "/",
     *     "be" : "/",
     *     "ci" : "/",
     *     "de" : "/",
     *     "it" : "/",
     *     "ma" : "/",
     *     "pt" : "/",
     *     "ro" : "/",
     * }, name="home")
     * @return Response
     */
    public function home(): Response
    {
        return new Response($this->renderView('home.html.twig'));
    }
}