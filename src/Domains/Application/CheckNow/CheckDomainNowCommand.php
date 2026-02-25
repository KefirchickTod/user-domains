<?php

declare(strict_types=1);

namespace Src\Domains\Application\CheckNow;

final readonly class CheckDomainNowCommand
{
    public function __construct(
        public int $domain_id,
        public int $user_id
    ) {}
}
