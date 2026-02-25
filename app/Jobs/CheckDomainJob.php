<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Src\Monitoring\Application\Check\CheckDomainCommand;
use Src\Monitoring\Application\Check\CheckDomainCommandHandler;

final class CheckDomainJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 60;

    public function __construct(
        public readonly int $domain_id,
        public readonly string $url,
        public readonly string $method,
        public readonly int $timeout_seconds
    ) {}

    public function handle(CheckDomainCommandHandler $handler): void
    {
        $handler->handle(new CheckDomainCommand(
            domain_id: $this->domain_id,
            url: $this->url,
            method: $this->method,
            timeout_seconds: $this->timeout_seconds
        ));
    }
}
