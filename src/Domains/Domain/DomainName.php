<?php

declare(strict_types=1);

namespace Src\Domains\Domain;

use Src\Shared\Domain\ValueObject\StringValueObject;

final class DomainName extends StringValueObject
{
    public function __construct(string $value)
    {
        if (trim($value) === '') {
            throw new \InvalidArgumentException('Domain name cannot be empty.');
        }

        parent::__construct(trim($value));
    }
}
