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
     *     "default" : "/legalInformation",
     *      "fr" : "/informations-legales",
     *      "en" : "/legal-information",
     *      "es" : "/informacion-legal",
     *      "de" : "/rechtliche-informationen",
     *      "it" : "/informazioni-legali",
     *      "pt" : "/informacao-legal",
     *      "be" : "/informations-legales",
     *      "ad" : "/informations-legales",
     *      "ro" : "/informatii-juridice",
     *      "ma" : "/informations-legales",
     *      "ci" : "/informations-legales"
     * }, name="_legal_information")
     * @return Response
     */
    public function legalInformation(): Response
    {
        return $this->render('pages/legalInformation.html.twig');
    }
}