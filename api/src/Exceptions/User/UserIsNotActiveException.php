<?php

namespace App\Exceptions\User;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserIsNotActiveException extends ConflictHttpException
{
    private const MESSAGE = 'the user with email %s is not active';

    public static function fromEmail(string $email): self
    {
        throw new self(sprintf(self::MESSAGE, $email));
    }

}