<?php

declare(strict_types=1);

namespace Src\Domains\Application\Delete;

final readonly class DeleteDomainCommand
{
    public function __construct(
        public int $domain_id,
        public int $user_id
    ) {}
}
