<?php

namespace App\EventSubscriber;

use App\Entity\Domain;
use App\Repository\DomainRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class LocaleSubscriber implements EventSubscriberInterface
{
    /**
     * @var DomainRepository
     */
    private $domainRepository;

    /**
     * @var array
     */
    private $convertLang = [
        'fr-BE' => 'fr',
        'fr-CH' => 'fr',
        'en-GB' => 'en',
        'gb' => 'en'
    ];

    /**
     * @var string
     */
    private $fallback = 'en';
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * LocaleSubscriber constructor.
     *
     * @param DomainRepository $domainRepository
     */
    public function __construct(DomainRepository $domainRepository, ParameterBagInterface $parameterBag)
    {
        $this->domainRepository = $domainRepository;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $event->getRequest()->getHttpHost();
        $httpHost = str_replace('test-', '', $event->getRequest()->getHttpHost());
        foreach ($this->domainRepository->getAll() as $item) {
            /** @var $item Domain */
            if (preg_match('/(' . $item->getDomain() . ')/', $httpHost)) {
                !array_key_exists($item->getLang(), $this->convertLang) ? $lang = $item->getLang() : $lang = $this->convertLang[$item->getLang()];
                $event->getRequest()->setLocale($lang ?? $this->parameterBag->get('fallback'));
                $session = $event->getRequest()->getSession();
                if ($session !== null){
                    $session->set('domain',  $item);
                }
            }
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
           'kernel.request' => 'onKernelRequest',
        ];
    }
}
