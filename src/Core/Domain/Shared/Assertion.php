<?php

declare(strict_types=1);

namespace App\Core\Domain\Shared;

use Assert\Assertion as BaseAssertion;

final class Assertion extends BaseAssertion
{
    /**
     * @phpstan-assert list<mixed> $array
     */
    public static function isList(mixed $array, string $message): void
    {
        self::isArray($array, $message);
        if (\count($array) > 0) {
            self::eq(array_keys($array), range(0, \count($array) - 1));
        }
    }
}
