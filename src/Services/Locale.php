<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Yaml\Yaml;

class Locale extends Container
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var $domain array
     */
    private $domain;

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

    /**
     * Locale constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->domain = Yaml::parseFile('../src/Data/domain.yaml');
        parent::__construct();
    }

    /**
     * @return Locale
     */
    public function setLocale(): self
    {
//        $httpHost = $this->requestStack->getMasterRequest()->server->get('HTTP_HOST');
        $httpHost = str_replace('test-','',$this->requestStack->getMasterRequest()->server->get('HTTP_HOST'));
        foreach ($this->domain as $item) {
            if (preg_match('/(' . $item['domain'] . ')/', $httpHost)) {
                !array_key_exists($item['lang'], $this->convertLang) ? $lang = $item['lang'] : $lang = $this->convertLang[$item['lang']];
//                $this->requestStack->getCurrentRequest()->setDefaultLocale($lang ?? $this->fallback);
                $this->requestStack->getCurrentRequest()->setLocale($lang ?? $this->fallback);
                $this->localematched = $item['lang'];
            }
        }
        return $this;
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