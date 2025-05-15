<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use Assert\Assertion;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class NonEmptyTextType extends Type
{
    public const string TYPE = 'non_empty_text';

    #[\Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getClobTypeDeclarationSQL($column);
    }

    /**
     * @return non-empty-string
     */
    #[\Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): string
    {
        if (\is_resource($value)) {
            $content = stream_get_contents($value);

            Assertion::string($content, 'Database returned a non string value');
            Assertion::notEmpty($content, 'Value can not be blank');

            return $content;
        }

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
