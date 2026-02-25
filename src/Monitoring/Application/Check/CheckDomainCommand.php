<?php

declare(strict_types=1);

namespace Src\Monitoring\Application\Check;

final readonly class CheckDomainCommand
{
    public function __construct(
        public int $domain_id,
        public string $url,
        public string $method,
        public int $timeout_seconds
    ) {}
}
