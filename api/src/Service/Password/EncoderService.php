<?php

declare(strict_types=1);

namespace App\Service\Password;

use App\Exceptions\Password\PasswordException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EncoderService
{

    private const MINIMUN_LENGTH = 6;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function generateEncodedPassword(PasswordAuthenticatedUserInterface $user, string $password): string
    {
        if(self::MINIMUN_LENGTH > strlen($password)){
            throw PasswordException::invalidLength();
        }
        return $this->userPasswordHasher->hashPassword($user, $password);
    }

    public function isValidPassword(User $user, string $oldPassword): bool
    {
        return $this->userPasswordHasher->isPasswordValid($user,$oldPassword);
    }
}