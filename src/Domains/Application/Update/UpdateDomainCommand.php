<?php

declare(strict_types=1);

namespace Src\Domains\Application\Update;

final readonly class UpdateDomainCommand
{
    public function __construct(
        public int $domain_id,
        public int $user_id,
        public string $name,
        public string $url,
        public bool $is_active
    ) {}
}
