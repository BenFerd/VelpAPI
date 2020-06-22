<?php

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Advert;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DateTimeAdvertSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setDateTimeForAdvert', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setDateTimeForAdvert(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($result instanceof Advert && $method === 'POST')
        {
            $result->setPubDate(new DateTime());
        }
    }
}