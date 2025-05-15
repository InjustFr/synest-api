<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use Assert\Assertion;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class NonEmptyStringType extends Type
{
    public const string TYPE = 'non_empty_string';

    #[\Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @return non-empty-string
     */
    #[\Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): string
    {
        Assertion::string($value, 'Database returned a non string value');
        Assertion::notEmpty($value, 'Value can not be blank');

        return $value;
    }

    /**
     * @return non-empty-string
     */
    #[\Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        Assertion::string($value, 'Can not map a non string to a string database value');
        Assertion::notEmpty($value, 'Value can not be empty');

        return $value;
    }

    #[\Override]
    public function getName(): string
    {
        return self::TYPE;
    }

    #[\Override]
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
