<?php

namespace App\Controller\Front;


use App\Services\Locale;
use Psr\SimpleCache\CacheInterface;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RenderController extends AbstractController
{
    /**
     * @param                $route
     * @param CacheInterface $cache
     * @param Locale         $locale
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getDomains($route, CacheInterface $cache, Locale $locale)
    {
        $cacheKey = 'domains-' . $route . $locale->setLocale()->getLocalematched();
        if (!$cache->has($cacheKey)) {
            $render = $this->render('render/domains.html.twig', [
                'domains' => $locale->getAllMainDomain(),
                'locale' => $locale->setLocale()->getLocalematched() ?? $locale->getFallback(),
                'route' => $route
            ]);
            $cache->set($cacheKey, $render);
        }
        return $cache->get($cacheKey);
    }

    /**
     * @return Response
     */
    public function getHeader()
    {
        return $this->render('render/header.html.twig');
    }

    public function getCheckboxCascade($entities, $form)
    {
        $content = '<div class="checkboxCascade"><ul>';
        foreach ($entities as $item) {
            $content .= '<li>';
            $content .= $this->renderView('form/checkboxCascade.html.twig', [
                'entity' => $item,
                'form' => $form
            ]);
            $content .= '</li>';
        }
        $content .= '</ul></div>';
        return new Response($content);
    }

    /**
     * @param CacheInterface $cache
     * @param Locale         $locale
     *
     * @return Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getSlider(CacheInterface $cache, Locale $locale): Response
    {
        $key = 'slider-' . $locale->setLocale()->getLocalematched();
        if (!$cache->has($key)) {
            $render = $this->renderView('render/slider.html.twig', [
                'locale' => $locale->getLocalematched() ?? $locale->getFallback(),
            ]);
            $cache->set($key, $render, 3600);
        }
        return new Response($cache->get($key));
    }

    /**
     * @param CacheInterface   $cache
     * @param Locale           $locale
     * @param ClientRepository $clientRepository
     *
     * @return string
     * @throws \Doctrine\ORM\Query\QueryException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getBottom(CacheInterface $cache, Locale $locale, ClientRepository $clientRepository)
    {
        $key = 'getBottom-5454zdzd-' . $locale->getLocalematched();
        if (!$cache->has($key)) {
            $cache->set($key, $this->render('parts/bottom.html.twig', [
                'clients' => $clientRepository->lastClients(4)
            ]), 3600);
        }
        return $cache->get($key);
    }

}