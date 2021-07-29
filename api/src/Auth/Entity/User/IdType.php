<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class IdType extends GuidType
{
    public const NAME = 'auth_user_id';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Id ? $value->getValue() : $value;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return Id|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Id
    {
        return !empty($value) ? new Id((string)$value) : null;
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
