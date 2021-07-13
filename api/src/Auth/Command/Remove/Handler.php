<?php

declare(strict_types=1);

namespace App\Auth\Command\Remove;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;
use App\Flusher;
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
     * Handler for remove user by id
     * @param Command $command
     * @throws DomainException if try remove active user
     */
    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        $user->remove(); // set status remove

        $this->users->remove($user); // remove user from db

        $this->flusher->flush();
    }
}
