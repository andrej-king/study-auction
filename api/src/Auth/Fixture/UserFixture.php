<?php

declare(strict_types=1);

namespace App\Auth\Fixture;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

/**
 * Class UserFixture
 * Insert fake data in dev db
 */
class UserFixture extends AbstractFixture
{
    // 'password'
    private const PASSWORD_HASH = 'fae09623861acfbb3c6d8272fe6403bd2861dc5509b68e2975c67fe45f765
    016ac98cfc7f85a1651e4e31a0ef6e681243d9b3fc7afc6ea909a28bd76';

    public function load(ObjectManager $manager): void
    {
        $user = User::requestJoinByEmail(
            new Id('00000000-0000-0000-0000-000000000001'),
            $date = new DateTimeImmutable('-30 days'),
            new Email('user@app.test'),
            self::PASSWORD_HASH,
            new Token($value = Uuid::uuid4()->toString(), $date->modify('+1 day'))
        );

        $user->confirmJoin($value, $date);

        $manager->persist($user);

        $manager->flush();
    }
}
