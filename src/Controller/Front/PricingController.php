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
     *     "fr" : "/pricing-fr",
     *     "en" : "/pricing-en",
     *     "es" : "/pricing-es",
     *     "ad" : "/pricing-ad",
     *     "be" : "/pricing-be",
     *     "ci" : "/pricing-ci",
     *     "de" : "/pricing-de",
     *     "it" : "/pricing-it",
     *     "ma" : "/pricing-ma",
     *     "pt" : "/pricing-pt",
     *     "ro" : "/pricing-ro",
     * }, name="_pricing")
     * @return Response
     */
    public function legalInformation(): Response
    {
        return $this->render('pages/pricing.html.twig');
    }
}