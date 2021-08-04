<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DomainException;

class UserRepository
{
    private EntityManagerInterface $em;
    private EntityRepository $repo;

    /**
     * UserRepository constructor.
     *
     * @param EntityManagerInterface $em
     * @param EntityRepository       $repo
     */
    public function __construct(EntityManagerInterface $em, EntityRepository $repo)
    {
        $this->em = $em;
        $this->repo = $repo;
    }

    /**
     * @param Email $email
     *
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.email = :email')
            ->setParameter(':email', $email->getValue())
            ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param Network $network
     *
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function hasByNetwork(Network $network): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->innerJoin('t.network', 'n')
            ->andWhere('n.network.name = :name and n.network.identity = :identity')
            ->setParameter(':name', $network->getName())
            ->setParameter(':identity', $network->getIdentity())
            ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param Id $id
     * @return User
     * @throws DomainException
     */
    public function get(Id $id): User
    {
        /** @psalm-suppress MixedAssignment */
        if (!$user = $this->repo->find($id->getValue())) {
            throw new DomainException('User is not found.');
        }

        /** @var User $user */
        return $user;
    }

    /**
     * Get user by email
     * @param Email $email
     * @return User
     * @throws DomainException
     */
    public function getByEmail(Email $email): User
    {
        /** @psalm-suppress MixedAssignment */
        if (!$user = $this->repo->findOneBy(['email' => $email->getValue()])) {
            throw new DomainException('User is not found.');
        }

        /** @var User $user */
        return $user;
    }


    /**
     * @param string $token
     *
     * @return User|object|null
     * @psalm-return User|null
     */
    public function findByJoinConfirmToken(string $token): ?User
    {
        /** @psalm-var User|null */
        return $this->repo->findOneBy(['joinConfirmToken.value' => $token]);
    }

    /**
     * @param string $token
     *
     * @return User|object|null
     * @psalm-return User|null
     */
    public function findByNewEmailToken(string $token): ?User
    {
        /** @psalm-var User|null */
        return $this->repo->findOneBy(['newEmailToken.value' => $token]);
    }

    /**
     * @param string $token
     * @return User|object|null
     * @psalm-return User|null
     */
    public function findByPasswordResetToken(string $token): ?User
    {
        /** @psalm-var User|null */
        return $this->repo->findOneBy(['passwordResetToken.value' => $token]);
    }

    /**
     * Add user
     *
     * @param User $user
     */
    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    /**
     * Remove user
     *
     * @param User $user
     */
    public function remove(User $user): void
    {
        $this->em->persist($user);
    }
}
