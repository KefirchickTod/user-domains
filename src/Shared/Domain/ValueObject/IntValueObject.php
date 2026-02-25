<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

abstract class IntValueObject
{
    public function __construct(
        protected readonly int $value
    ) {
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(IntValueObject $other): bool
    {
        return $this->value() === $other->value();
    }

    public function isBiggerThan(IntValueObject $other): bool
    {
        return $this->value() > $other->value();
    }

    public function __toString(): string
    {
        return (string) $this->value();
    }
}
