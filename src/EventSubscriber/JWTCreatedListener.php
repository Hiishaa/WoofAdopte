<?php

namespace App\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: "lexik_jwt_authentication.on_jwt_created", method: 'onJWTCreated')]
final class JWTCreatedListener
{

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $payload = $event->getData();

        $payload['email'] = $event->getUser()->getUserIdentifier();

        $event->setData($payload);

        // $payload = $event->getData();
        // /** @var \App\Entity\User $user */
        // $user = $event->getUser();

        // $payload['email'] = $user->getUserIdentifier();
        // // $payload['count'] = count($user->getRoles());
        // $event->setData($payload);
    }
}