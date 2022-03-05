<?php

namespace App\Api\Listener;

use App\Exceptions\User\UserIsNotActiveException;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $user = $event->getUser();

        if(!$user->isActive())
            throw UserIsNotActiveException::fromEmail($user->getUsername());

    }
}