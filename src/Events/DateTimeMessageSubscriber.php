<?php

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Message;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DateTimeMessageSubscriber implements EventSubscriberInterface{


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setDateTimeForUser',
            EventPriorities::PRE_VALIDATE]
        ];
    }

    // Fonction qui set la date de création automatiquement.
    public function setDateTimeForUser(ViewEvent $event){

        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($result instanceof Message && $method === "POST") {
            $result->setDate(new DateTime());
        }
    }
}