<?php

namespace App\Controller\Front;

use App\Services\Locale;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LegalInformationController extends AbstractController
{

    /**
     * @var Locale
     */
    private $locale;

    /**
     * HomeController constructor.
     * @param Locale $locale
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(Locale $locale)
    {
        $locale->setLocale();
        $this->locale = $locale;
    }

    /**
     * @Route({
     *     "default" : "/legal-Information",
     *     "fr" : "/legal-Information-fr",
     *     "en" : "/legal-Information-en",
     *     "es" : "/legal-Information-es",
     *     "ad" : "/legal-Information-ad",
     *     "be" : "/legal-Information-be",
     *     "ci" : "/legal-Information-ci",
     *     "de" : "/legal-Information-de",
     *     "it" : "/legal-Information-it",
     *     "ma" : "/legal-Information-ma",
     *     "pt" : "/legal-Information-pt",
     *     "ro" : "/legal-Information-ro",
     * }, name="_legal_information")
     * @return Response
     */
    public function legalInformation(): Response
    {
        return $this->render('pages/legalInformation.html.twig');
    }
}