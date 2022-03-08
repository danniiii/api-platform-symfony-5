<?php

namespace App\Security\Http\Authentication;

use App\Exceptions\User\UserIsNotActiveException;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private UserRepository $userRepository;
    private JWTTokenManagerInterface $JWTTokenManager;

    public function __construct(UserRepository $userRepository, JWTTokenManagerInterface $JWTTokenManager)
    {
        $this->userRepository = $userRepository;
        $this->JWTTokenManager = $JWTTokenManager;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        return $this->handleAuthenticationSuccess($token->getUser());
    }

    public function handleAuthenticationSuccess(UserInterface $user, $jwt = null)
    {
        if (null === $jwt) {
            $jwt = $this->JWTTokenManager->create($user);
        }
        if (!$user->isActive()) {
            throw UserIsNotActiveException::fromEmail($user->getUsername());
        }

        return new JWTAuthenticationSuccessResponse($jwt, [], []);
    }
}
