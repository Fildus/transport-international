<?php

namespace App\EventSubscriber;

use App\Controller\Front\ProfessionalProfileController;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ConnectionPhoneSubscriber implements EventSubscriberInterface
{
    public function onKernelController(FilterControllerEvent $event): void
    {
        $c = $event->getController();
        if (isset($c[0], $c[1]) && $c[0] instanceof ProfessionalProfileController && $c[1] === 'profile') {
            $session = $event->getRequest()->getSession();
            $key = 'tryHaveNumber';
            if ($session) {
                if ($session->has($key)) {

                    /**
                     * @var $times ArrayCollection
                     */
                    $times = $session->get($key);
                    $times->add(time());

                    foreach ($times as $k => $v) {
                        if ($v + 300 < time()) {
                            $times->removeElement($v);
                        }
                    }
                } else {
                    $session->set($key, new ArrayCollection([0 => time()]));
                }
            }
        }

    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
