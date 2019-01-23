<?php

namespace App\Controller\Front;

use App\Services\Locale;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PricingController extends AbstractController
{

    /**
     * @var Locale
     */
    private $locale;

    /**
     * HomeController constructor.
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
     *     "default" : "/pricing",
     *      "fr" : "/tarification",
     *      "en" : "/pricing",
     *      "es" : "/precios",
     *      "de" : "/preisgestaltung",
     *      "it" : "/prezzi",
     *      "pt" : "/precos",
     *      "be" : "/tarification",
     *      "ad" : "/tarification",
     *      "ro" : "/pret",
     *      "ma" : "/tarification",
     *      "ci" : "/tarification"
     * }, name="_pricing")
     * @return Response
     */
    public function legalInformation(): Response
    {
        return $this->render('pages/pricing.html.twig');
    }
}