<?php

namespace App\Repository;

use App\Entity\User;
use App\Exceptions\User\UserNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class UserRepository extends BaseRepository
{

    /**
     * @return string
     */
    protected static function entityClass(): string
    {
        return User::class;
    }


    public function findOneByIdOrFail(string $id): User
    {
        $user = $this->objectRepository->findOneBy(['id' => $id]);
        if($user === null)
            throw UserNotFoundException::fromId($id);
        return $user;
    }

    public function findOneByEmailOrFail(string $email): User
    {
        $user = $this->objectRepository->findOneBy(['email' => $email]);
        if($user === null)
            throw UserNotFoundException::fromEmail($email);
        return $user;
    }

   public function findOneInactiveByIdAndTokenOrFail(string $id, string $token): User
    {
        $user = $this->objectRepository->findOneBy(['id' => $id, 'token' => $token, 'active' => false]);
        if($user === null)
            throw UserNotFoundException::fromIdAndToken($id,$token);
        return $user;
    }

    public function findOneByIdAndResetPasswordTokenOrFail(string $id, string $resetPasswordToken): User
    {
        $user = $this->objectRepository->findOneBy(['id' => $id, 'resetPasswordToken' => $resetPasswordToken, 'active' => true]);
        if($user === null)
            UserNotFoundException::fromIdAndResetPasswordToken($id, $resetPasswordToken);
        return $user;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(User $user): void
    {
        $this->saveEntity($user);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(User $user): void
    {
        $this->removeEntity($user);
    }
}