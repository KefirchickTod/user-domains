<?php

declare(strict_types=1);

namespace Src\Domains\Domain;

use Src\Shared\Domain\ValueObject\StringValueObject;

final class DomainUrl extends StringValueObject
{
    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException(sprintf('Invalid domain URL: "%s".', $value));
        }

        parent::__construct($value);
    }
}
