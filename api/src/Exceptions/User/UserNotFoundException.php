<?php

namespace App\Exceptions\User;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends NotFoundHttpException
{
    private const MESSAGE = 'User with email %s not found';

    public static function fromEmail(string $email): self
    {
        throw new self(sprintf(self::MESSAGE, $email));
    }

    public static function fromIdAndToken(string $id, string $token): self
    {
        throw new self(sprintf('User with id %s and token %s not found', $id, $token));
    }
}


