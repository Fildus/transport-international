<?php

namespace App\Controller;


use App\Services\Locale;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExceptionController extends AbstractController
{

    /**
     * ExceptionController constructor.
     * @param Locale $locale
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct(Locale $locale)
    {
        $locale->setLocale();
    }

    /**
     * @return Response
     */
    public function showException(): Response
    {
        return new Response($this->renderView('bundles/TwigBundle/Exception/error404.html.twig'), Response::HTTP_NOT_FOUND);
    }
}