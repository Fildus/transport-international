<?php

namespace App\Controller\Front;

use App\Services\Locale;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        return new Response($this->renderView('pages/home.html.twig'));
    }
}