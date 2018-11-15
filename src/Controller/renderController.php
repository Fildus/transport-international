<?php

namespace App\Controller;


use App\Services\Locale;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class renderController extends AbstractController
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

    public function getDomains($route)
    {
        return $this->render('render/domains.html.twig',[
            'domains' => $this->locale->getAllMainDomain(),
            'locale' => $this->locale->getLocalematched() ?? $this->locale->getFallback(),
            'route'=> $route
        ]);
    }

}