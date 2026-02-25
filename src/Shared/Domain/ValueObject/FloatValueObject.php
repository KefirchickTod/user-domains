<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

abstract class FloatValueObject
{
    public function __construct(
        protected readonly float $value
    ) {
    }

    public function value(): float
    {
        return $this->value;
    }

    public function equals(FloatValueObject $other): bool
    {
        return abs($this->value() - $other->value()) < PHP_FLOAT_EPSILON;
    }

    public function isBiggerThan(FloatValueObject $other): bool
    {
        return $this->value() > $other->value();
    }

    public function __toString(): string
    {
        return (string) $this->value();
    }
}
