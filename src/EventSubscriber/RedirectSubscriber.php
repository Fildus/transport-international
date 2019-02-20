<?php

namespace App\EventSubscriber;

use App\Controller\Front\ProfessionalProfileController;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class RedirectSubscriber extends AbstractController implements EventSubscriberInterface
{
    public function onKernelController(FilterControllerEvent $event)
    {
//        $kernelHost = $event->getRequest()->getHttpHost();
//        if ((int)preg_match('#^www#', $kernelHost) === 0) {
//            header('Location: ' . 'https://www.' . $kernelHost, true, 301);
//            exit();
//        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
