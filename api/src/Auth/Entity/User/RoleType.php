<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class RoleType extends StringType
{
    public const NAME = 'auth_user_role';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Role ? $value->getName() : $value;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return Role|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Role
    {
        return !empty($value) ? new Role((string)$value) : null;
    }

    /**
     * Get column name in database
     *
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * Add comments in database for each column about original types in PHP.
     *
     * @param AbstractPlatform $platform
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
