<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

abstract class StringValueObject
{
    public function __construct(
        protected readonly string $value
    ) {
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(StringValueObject $other): bool
    {
        return $this->value() === $other->value();
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public static function fromString(string $value): self
    {
        return new static($value);
    }
}
