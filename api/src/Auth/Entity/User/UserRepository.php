<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

/**
 * Interface UserRepository
 */
interface UserRepository
{
    /**
     * @param Email $email
     * @return bool
     */
    public function hasByEmail(Email $email): bool;

    /**
     * @param NetworkIdentity $identity
     * @return bool
     */
    public function hasByNetwork(NetworkIdentity $identity): bool;

    /**
     * @param string $token
     * @return User|null
     */
    public function findByConfirmToken(string $token): ?User;

    /**
     * @param User $user
     */
    public function add(User $user): void;
}
