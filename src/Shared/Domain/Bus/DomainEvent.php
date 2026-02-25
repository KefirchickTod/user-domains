<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Bus;

interface DomainEvent
{
    public function occurredOn(): \DateTimeImmutable;
}
