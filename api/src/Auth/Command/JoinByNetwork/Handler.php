<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByNetwork;

use App\Auth\Entity\User\{Email, Id, Network, User, UserRepository};
use App\Flusher;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UserRepository $users;
    private Flusher $flusher;

    /**
     * Handler constructor.
     * @param UserRepository $users
     * @param Flusher        $flusher
     */
    public function __construct(UserRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    /**
     * Join by network handler
     * @param Command $command
     * @throws DomainException
     */
    public function handle(Command $command): void
    {
        $network = new Network($command->network, $command->identity);
        $email = new Email($command->email);

        if ($this->users->hasByNetwork($network)) {
            throw new DomainException('User with this network already exists.');
        }

        if ($this->users->hasByEmail($email)) {
            throw new DomainException('User with this email already exists.');
        }

        $user = User::joinByNetwork(
            Id::generate(),
            new DateTimeImmutable(),
            $email,
            $network
        );

        $this->users->add($user);

        $this->flusher->flush();
    }
}
