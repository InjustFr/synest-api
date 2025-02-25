<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use Assert\Assert;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class NonEmptyStringType extends Type
{
    public const string TYPE = 'non_empty_string';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @param non-empty-string $value
     *
     * @return non-empty-string
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): string
    {
        Assert::that($value)
            ->string('Database returned a non string value')
            ->notBlank('Value can not be blank');

        return $value;
    }

    /**
     * @param non-empty-string $value
     *
     * @return non-empty-string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        Assert::that($value)
            ->string('Can not map a non string to a string database value')
            ->notBlank('Value can not be blank');

        return $value;
    }

    public function getName(): string
    {
        return self::TYPE;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
