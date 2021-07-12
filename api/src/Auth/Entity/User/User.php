<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use App\Auth\Service\PasswordHasher;
use ArrayObject;
use DateTimeImmutable;
use DomainException;

class User
{
    private Id $id;
    private DateTimeImmutable $date;
    private Email $email;
    private ?string $passwordHash = null;
    private Status $status;
    private ?Token $joinConfirmToken = null;
    private ArrayObject $networks;
    private ?Token $passwordResetToken = null;

    /**
     * User constructor.
     * @param Id                $id
     * @param DateTimeImmutable $date
     * @param Email             $email
     * @param Status            $status
     */
    private function __construct(Id $id, DateTimeImmutable $date, Email $email, Status $status)
    {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->status = $status;
        $this->networks = new ArrayObject();
    }

    /**
     * Named constructor for create join user with email
     * @param Id                $id
     * @param DateTimeImmutable $date
     * @param Email             $email
     * @param string            $passwordHash
     * @param Token             $token
     * @return self
     */
    public static function requestJoinByEmail(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        string $passwordHash,
        Token $token
    ): self {
        $user = new self($id, $date, $email, Status::wait());
        $user->passwordHash = $passwordHash;
        $user->joinConfirmToken = $token;
        return $user;
    }

    /**
     * Named constructor for create join user with social networks
     * @param Id                $id
     * @param DateTimeImmutable $date
     * @param Email             $email
     * @param NetworkIdentity   $identity
     * @return self
     */
    public static function joinByNetwork(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        NetworkIdentity $identity
    ): self {
        $user = new self($id, $date, $email, Status::active());
        $user->networks->append($identity);
        return $user;
    }

    /**
     * Attach social network to user
     * @param NetworkIdentity $identity
     * @throws DomainException
     */
    public function attachNetwork(NetworkIdentity $identity): void
    {
        /** @var NetworkIdentity $existing */
        foreach ($this->networks as $existing) {
            if ($existing->isEqualTo($identity)) {
                throw new DomainException('Network is already attached.');
            }
        }
        $this->networks->append($identity);
    }

    /**
     * Will save token only if not yet exists
     * @param Token             $token
     * @param DateTimeImmutable $date
     * @throws DomainException
     */
    public function requestPasswordReset(Token $token, DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }
        if ($this->passwordResetToken !== null && !$this->passwordResetToken->isExpiredTo($date)) {
            throw new DomainException('Resetting is already requested.');
        }
        $this->passwordResetToken = $token;
    }

    /**
     * @param string            $token
     * @param DateTimeImmutable $date
     * @param string            $hash
     * @throws DomainException
     */
    public function resetPassword(string $token, DateTimeImmutable $date, string $hash): void
    {
        if ($this->passwordResetToken === null) {
            throw new DomainException('Resetting is not requested.');
        }
        $this->passwordResetToken->validate($token, $date);
        $this->passwordResetToken = null;
        $this->passwordHash = $hash;
    }

    /**
     * @param string         $current current password
     * @param string         $new     new password
     * @param PasswordHasher $hasher
     * @throws DomainException
     */
    public function changePassword(string $current, string $new, PasswordHasher $hasher): void
    {
        if ($this->passwordHash === null) {
            throw new DomainException('User does not have an old password.');
        }
        if (!$hasher->validate($current, $this->passwordHash)) {
            throw new DomainException('Incorrect current password.');
        }
        $this->passwordHash = $hasher->hash($new);
    }

    /**
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->status->isWait();
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    /**
     * @return Token|null
     */
    public function getJoinConfirmToken(): ?Token
    {
        return $this->joinConfirmToken;
    }

    /**
     * @return Token|null
     */
    public function getPasswordResetToken(): ?Token
    {
        return $this->passwordResetToken;
    }

    /**
     * Confirm and reset token
     * @param string            $token
     * @param DateTimeImmutable $date
     * @throws DomainException
     */
    public function confirmJoin(string $token, DateTimeImmutable $date): void
    {
        if ($this->joinConfirmToken === null) {
            throw new DomainException('Confirmation is not required.');
        }

        $this->joinConfirmToken->validate($token, $date);
        $this->status = Status::active();
        $this->joinConfirmToken = null;
    }

    /**
     * @return NetworkIdentity[]
     */
    public function getNetworks(): array
    {
        /** @var NetworkIdentity[] */
        return $this->networks->getArrayCopy();
    }
}
