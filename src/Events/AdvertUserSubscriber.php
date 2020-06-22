<?php

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Advert;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class AdvertUserSubscriber implements EventSubscriberInterface{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setUserForAdvert', EventPriorities::PRE_VALIDATE]
        ];
    }
    
    public function setUserForAdvert(ViewEvent $event){
        $advert = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($advert instanceof Advert && $method === 'POST'){
            $user = $this->security->getUser();
            $advert->setUser($user);
        }
    }

}