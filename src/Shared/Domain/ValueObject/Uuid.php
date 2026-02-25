<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

use InvalidArgumentException;
use JsonSerializable;
use Ramsey\Uuid\Uuid as RamseyUuid;

abstract class Uuid implements \Stringable, JsonSerializable
{
    public function __construct(
        protected readonly string $value
    ) {
        $this->ensureIsValidUuid($value);
    }

    public static function random(): static
    {
        return new static(RamseyUuid::uuid4()->toString());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->value() === $other->value();
    }

    public function __toString(): string
    {
        return $this->value();
    }

    private function ensureIsValidUuid(string $id): void
    {
        if (!RamseyUuid::isValid($id)) {
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $id));
        }
    }

    public function jsonSerialize(): string
    {
        return $this->value();
    }

    public static function fromString(string $uuid): static
    {
        return new static($uuid);
    }
}
