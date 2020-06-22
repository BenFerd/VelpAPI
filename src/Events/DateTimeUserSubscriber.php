<?php

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DateTimeUserSubscriber implements EventSubscriberInterface{


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

        if($result instanceof User && $method === "POST") {
            $result->setRegisterDate(new DateTime());
        }
    }
}