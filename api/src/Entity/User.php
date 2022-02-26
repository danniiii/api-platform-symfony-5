<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(name="id", length=36)
     * @ORM\Id
     */
    private string $id;

    /**
     * @ORM\Column(name="name", length=100)
     */
    private string $name;

    /**
     * @ORM\Column(name="email", length=100)
     */
    private string $email;

    /**
     * @ORM\Column(name="password", length=100, nullable=true)
     */
    private ?string $password;

    /**
     * @ORM\Column(name="avatar", length=255, nullable=true)
     */
    private ?string $avatar;

    /**
     * @ORM\Column(name="token", length=100, nullable=true)
     */
    private ?string $token;

    /**
     * @ORM\Column(name="reset_password_token", length=100, nullable=true)
     */
    private ?string $resetPasswordToken;

    /**
     * @ORM\Column(name="active")
     */
    private bool $active;

    /**
     * @ORM\Column(name="created_at")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at")
     */
    private \DateTime $updatedAt;

    public function __construct(string $name, string $email){

        $this->id = Uuid::v4()->toRfc4122();
        $this->name = $name;
        $this->setEmail($email);
        $this->password = null;
        $this->avatar = null;
        $this->token = \sha1(\uniqid());
        $this->resetPasswordToken = null;
        $this->active = false;
        $this->createdAt = new DateTime();
        $this->markAsUpdated();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        if(!\filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new LogicException('Invalid mail');
        }
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string|null $avatar
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return string|null
     */
    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    /**
     * @param string|null $resetPasswordToken
     */
    public function setResetPasswordToken(?string $resetPasswordToken): void
    {
        $this->resetPasswordToken = $resetPasswordToken;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function markAsUpdated(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getRoles() : array
    {
        return [];
    }

    public function getSalt() : void
    {

    }
    public function eraseCredentials() : void
    {

    }

    public function getUsername() : string
    {
        return $this->email;
    }

}