<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Aggregate;

use Src\Shared\Domain\Bus\DomainEvent;

abstract class AggregateRoot
{
    private array $domain_events = [];

    final public function pullDomainEvents(): array
    {
        $domain_events       = $this->domain_events;
        $this->domain_events = [];

        return $domain_events;
    }

    protected function record(DomainEvent $domain_event): void
    {
        $this->domain_events[] = $domain_event;
    }
}
