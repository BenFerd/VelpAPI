<?php

namespace App\Events;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
/**
 * modification des données du jwtToken pour récupérer l'id.
 */
class JwtHeadersSubscriber {

    public function updateJwtData(JWTCreatedEvent $event)
    {
       $user = $event->getUser();
       $data = $event->getData();
       $data['id'] = $user->getId();

       $event->setData($data);
    }
}