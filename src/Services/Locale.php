<?php

namespace App\Services;

use App\Entity\Domain;
use App\Repository\DomainRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Yaml\Yaml;

class Locale
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    private $domainRepository;

    private $convertLang = [
        'fr-BE' => 'fr',
        'fr-CH' => 'fr',
        'en-GB' => 'en'
    ];

    private $fallback = 'en';

    /**
     * @var $localematched string
     */
    private $localematched;

    private $domain;

    /**
     * Locale constructor.
     * @param RequestStack $requestStack
     * @param DomainRepository $domainRepository
     */
    public function __construct(RequestStack $requestStack, DomainRepository $domainRepository)
    {
        $this->requestStack = $requestStack;
        $this->domainRepository = $domainRepository;
    }

    /**
     * @return Locale
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function setLocale(): self
    {
        $httpHost = str_replace('test-','',$this->requestStack->getMasterRequest()->getHttpHost());
        foreach ($this->domainRepository->getAll() as $item) {
            /** @var $item Domain */
            if (preg_match('/(' . $item->getDomain() . ')/', $httpHost)) {
                !array_key_exists($item->getLang(), $this->convertLang) ? $lang = $item->getLang() : $lang = $this->convertLang[$item->getLang()];
                $this->requestStack->getCurrentRequest()->setLocale($lang ?? $this->fallback);
                $this->localematched = $lang;
                $this->domain = $item;
            }
        }
        return $this;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return string
     */
    public function getLocalematched(): string
    {
        return $this->localematched;
    }

    public function getFallback()
    {
        return $this->fallback;
    }

    /**
     * @return array
     */
    public function getAllMainDomain()
    {
        return [
            'fr' => [
                'domain' => 'transport-international.com',
                'hreflang' => 'fr',
                'img' => 'fr.png',
                'alt' => 'français'
            ],
            'en' => [
                'domain' => 'transportation-directory.com',
                'hreflang' => 'en-gb',
                'img' => 'gb.png',
                'alt' => 'anglais'
            ],
            'es' => [
                'domain' => 'guia-transporte.com',
                'hreflang' => 'es',
                'img' => 'es.png',
                'alt' => 'espagnol'
            ],
            'de' => [
                'domain' => 'transport-unternehmen.net',
                'hreflang' => 'de',
                'img' => 'de.png',
                'alt' => 'allemand'
            ],
            'it' => [
                'domain' => 'trasporto-italia.com',
                'hreflang' => 'it',
                'img' => 'it.png',
                'alt' => 'italien'
            ],
            'pt' => [
                'domain' => 'armazenagem-portugal.com',
                'hreflang' => 'pt',
                'img' => 'pt.png',
                'alt' => 'portugal'
            ],
            'be' => [
                'domain' => 'transport-international.be',
                'hreflang' => 'nl-be',
                'img' => 'be.png',
                'alt' => 'belge'
            ],
            'ad' => [
                'domain' => 'anuari-transport.com',
                'hreflang' => 'ca-ad',
                'img' => 'ad.png',
                'alt' => 'andorra'
            ],
            'ro' => [
                'domain' => 'anuar-transport.com',
                'hreflang' => 'ro',
                'img' => 'ro.png',
                'alt' => 'roumanie'
            ],
            'ma' => [
                'domain' => 'transports-maroc.com',
                'hreflang' => 'fr-ma',
                'img' => 'ma.png',
                'alt' => 'maroc'
            ],
            'ci' => [
                'domain' => 'transport-cotedivoire.com',
                'hreflang' => 'fr-ci',
                'img' => 'ci.png',
                'alt' => 'cote d\'ivoire'
            ]
        ];
    }
}